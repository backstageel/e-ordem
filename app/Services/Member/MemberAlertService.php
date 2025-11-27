<?php

namespace App\Services\Member;

use App\Models\Member;
use App\Models\MemberQuota;

class MemberAlertService
{
    public function sendQuotaReminder(Member $member, MemberQuota $quota): void
    {
        if (! config('members.notifications.quota_reminders_enabled', true)) {
            return;
        }

        if (! $member->person || ! $member->person->user) {
            return;
        }

        try {
            $member->person->user->notify(
                new \App\Notifications\Member\QuotaReminderNotification($member, $quota)
            );
        } catch (\Throwable $e) {
            \Log::warning('Failed to send quota reminder', [
                'member_id' => $member->id,
                'quota_id' => $quota->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function sendQuotaOverdueAlert(Member $member, MemberQuota $quota): void
    {
        if (! $member->person || ! $member->person->user) {
            return;
        }

        try {
            $member->person->user->notify(
                new \App\Notifications\Member\QuotaOverdueNotification($member, $quota)
            );
        } catch (\Throwable $e) {
            \Log::warning('Failed to send quota overdue alert', [
                'member_id' => $member->id,
                'quota_id' => $quota->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function sendSuspensionWarning(Member $member): void
    {
        if (! config('members.suspension.auto_suspend_enabled', true)) {
            return;
        }

        if (! $member->person || ! $member->person->user) {
            return;
        }

        $warningDays = config('members.suspension.warning_days_before', 7);
        $suspensionDays = config('members.suspension.days_before_suspension', 90);

        $oldestOverdue = $member->overdueQuotas()
            ->orderBy('due_date', 'asc')
            ->first();

        if (! $oldestOverdue) {
            return;
        }

        $daysOverdue = now()->diffInDays($oldestOverdue->due_date);
        $daysUntilSuspension = $suspensionDays - $daysOverdue;

        if ($daysUntilSuspension <= $warningDays && $daysUntilSuspension > 0) {
            try {
                $member->person->user->notify(
                    new \App\Notifications\Member\SuspensionWarningNotification($member, $daysUntilSuspension)
                );
            } catch (\Throwable $e) {
                \Log::warning('Failed to send suspension warning', [
                    'member_id' => $member->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    public function sendDocumentExpiringAlert(Member $member, $document): void
    {
        if (! config('members.notifications.document_expiry_alerts_enabled', true)) {
            return;
        }

        if (! $member->person || ! $member->person->user) {
            return;
        }

        try {
            $member->person->user->notify(
                new \App\Notifications\DocumentExpiringNotification($document)
            );
        } catch (\Throwable $e) {
            \Log::warning('Failed to send document expiring alert', [
                'member_id' => $member->id,
                'document_id' => $document->id ?? null,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function sendDocumentExpiredAlert(Member $member, $document): void
    {
        if (! config('members.notifications.document_expiry_alerts_enabled', true)) {
            return;
        }

        if (! $member->person || ! $member->person->user) {
            return;
        }

        try {
            $member->person->user->notify(
                new \App\Notifications\DocumentExpiredNotification($document)
            );
        } catch (\Throwable $e) {
            \Log::warning('Failed to send document expired alert', [
                'member_id' => $member->id,
                'document_id' => $document->id ?? null,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function checkAndSendQuotaReminders(): void
    {
        $reminderDays = config('members.notifications.quota_reminder_days_before', 15);
        $reminderDate = now()->addDays($reminderDays)->startOfDay();

        $upcomingQuotas = MemberQuota::with('member.person.user')
            ->where('status', MemberQuota::STATUS_PENDING)
            ->where('due_date', '<=', $reminderDate)
            ->where('due_date', '>', now()->startOfDay())
            ->get();

        foreach ($upcomingQuotas as $quota) {
            if ($quota->member && $quota->member->status === Member::STATUS_ACTIVE) {
                $this->sendQuotaReminder($quota->member, $quota);
            }
        }
    }

    public function checkAndSendOverdueAlerts(): void
    {
        $overdueQuotas = MemberQuota::with('member.person.user')
            ->where(function ($q) {
                $q->where('status', MemberQuota::STATUS_OVERDUE)
                    ->orWhere(function ($query) {
                        $query->where('status', MemberQuota::STATUS_PENDING)
                            ->where('due_date', '<', now()->startOfDay());
                    });
            })
            ->where('due_date', '>=', now()->subDays(30)->startOfDay()) // Only recent overdue (last 30 days)
            ->get();

        foreach ($overdueQuotas as $quota) {
            if ($quota->member && $quota->member->status === Member::STATUS_ACTIVE) {
                $this->sendQuotaOverdueAlert($quota->member, $quota);
            }
        }
    }

    public function checkAndSendSuspensionWarnings(): void
    {
        $activeMembers = Member::where('status', Member::STATUS_ACTIVE)
            ->with(['person.user', 'quotaHistory'])
            ->get();

        foreach ($activeMembers as $member) {
            if ($member->overdueQuotas()->exists()) {
                $this->sendSuspensionWarning($member);
            }
        }
    }

    public function checkAndSendComplianceAlerts(): void
    {
        if (! config('members.notifications.compliance_alerts_enabled', true)) {
            return;
        }

        $activeMembers = Member::where('status', Member::STATUS_ACTIVE)
            ->with(['person.user', 'person.documents'])
            ->get();

        $complianceService = app(\App\Services\Member\MemberComplianceService::class);

        foreach ($activeMembers as $member) {
            if (! $member->person || ! $member->person->user) {
                continue;
            }

            $issues = $complianceService->checkMemberCompliance($member);

            if (! empty($issues)) {
                try {
                    // Send compliance alert notification
                    $member->person->user->notify(
                        new \App\Notifications\Member\ComplianceAlertNotification($member, $issues)
                    );
                } catch (\Throwable $e) {
                    \Log::warning('Failed to send compliance alert', [
                        'member_id' => $member->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }
    }
}
