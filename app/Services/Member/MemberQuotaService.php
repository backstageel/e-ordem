<?php

namespace App\Services\Member;

use App\Models\Member;
use App\Models\MemberQuota;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MemberQuotaService
{
    public function generateQuotasForMember(Member $member, int $year, ?int $month = null): void
    {
        $months = $month ? [$month] : range(1, 12);
        $amount = config('members.quota.default_amount', 4000.00);

        foreach ($months as $m) {
            $this->generateQuota($member, $year, $m, $amount);
        }
    }

    public function generateQuota(Member $member, int $year, int $month, ?float $amount = null): MemberQuota
    {
        $amount = $amount ?? config('members.quota.default_amount', 4000.00);
        $dueDate = Carbon::create($year, $month, 15);

        return MemberQuota::firstOrCreate(
            [
                'member_id' => $member->id,
                'year' => $year,
                'month' => $month,
            ],
            [
                'amount' => $amount,
                'due_date' => $dueDate,
                'status' => MemberQuota::STATUS_PENDING,
            ]
        );
    }

    public function calculateOverdueAmount(Member $member): float
    {
        return (float) $member->overdueQuotas()
            ->sum(DB::raw('amount + penalty_amount'));
    }

    public function checkMemberQuotaStatus(Member $member): string
    {
        return $member->getQuotaStatus();
    }

    public function shouldSuspendForQuotas(Member $member): bool
    {
        if ($member->status !== Member::STATUS_ACTIVE) {
            return false;
        }

        if (! config('members.suspension.auto_suspend_enabled', true)) {
            return false;
        }

        $suspensionDays = config('members.suspension.days_before_suspension', 90);
        $oldestOverdue = $member->overdueQuotas()
            ->orderBy('due_date', 'asc')
            ->first();

        if (! $oldestOverdue) {
            return false;
        }

        $daysOverdue = abs(now()->diffInDays($oldestOverdue->due_date));

        return $daysOverdue >= $suspensionDays;
    }

    public function markQuotaAsPaid(MemberQuota $quota, ?int $paymentId = null): void
    {
        $quota->update([
            'status' => MemberQuota::STATUS_PAID,
            'payment_date' => now(),
            'payment_id' => $paymentId,
            'penalty_amount' => 0, // Reset penalty if paid
        ]);
    }

    public function calculatePenalty(MemberQuota $quota): float
    {
        if ($quota->isPaid() || $quota->status === MemberQuota::STATUS_WAIVED) {
            return 0;
        }

        if (! $quota->isOverdue()) {
            return 0;
        }

        $penaltyPercentage = config('members.quota.penalty_percentage', 0.5);
        $daysOverdue = max(0, now()->diffInDays($quota->due_date));

        return $quota->amount * $penaltyPercentage;
    }

    public function updateOverdueQuotas(Member $member): void
    {
        $penaltyPercentage = config('members.quota.penalty_percentage', 0.5);
        $member->quotaHistory()
            ->where('status', MemberQuota::STATUS_PENDING)
            ->where('due_date', '<', now()->startOfDay())
            ->each(function ($quota) use ($penaltyPercentage) {
                $quota->update([
                    'status' => MemberQuota::STATUS_OVERDUE,
                    'penalty_amount' => $quota->amount * $penaltyPercentage,
                ]);
            });
    }
}
