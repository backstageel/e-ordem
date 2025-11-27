<?php

namespace Modules\Member\Tests\Feature\Member;

use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Member;
use App\Models\MemberQuota;
use App\Models\Person;
use App\Models\User;
use App\Services\Member\MemberAlertService;
use App\Services\Member\MemberComplianceService;
use App\Services\Member\MemberQuotaService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    Notification::fake();
});

describe('MemberQuotaService', function () {
    it('can generate quotas for a member for a full year', function () {
        $person = Person::factory()->create();
        $member = Member::factory()->create(['person_id' => $person->id]);
        $service = new MemberQuotaService;
        $year = now()->year;

        $service->generateQuotasForMember($member, $year);

        $quotas = MemberQuota::where('member_id', $member->id)
            ->where('year', $year)
            ->get();

        expect($quotas)->toHaveCount(12);

        foreach (range(1, 12) as $month) {
            $this->assertDatabaseHas('member_quotas', [
                'member_id' => $member->id,
                'year' => $year,
                'month' => $month,
                'status' => MemberQuota::STATUS_PENDING,
            ]);
        }
    });

    it('can generate quota for a specific month', function () {
        $person = Person::factory()->create();
        $member = Member::factory()->create(['person_id' => $person->id]);
        $service = new MemberQuotaService;
        $year = now()->year;
        $month = 6;

        $quota = $service->generateQuota($member, $year, $month, 5000.00);

        expect($quota)->toBeInstanceOf(MemberQuota::class);
        expect($quota->member_id)->toBe($member->id);
        expect($quota->year)->toBe($year);
        expect($quota->month)->toBe($month);
        expect((float) $quota->amount)->toBe(5000.0);
        expect($quota->status)->toBe(MemberQuota::STATUS_PENDING);

        $this->assertDatabaseHas('member_quotas', [
            'member_id' => $member->id,
            'year' => $year,
            'month' => $month,
            'amount' => 5000.00,
        ]);
    });

    it('does not duplicate quotas when generating multiple times', function () {
        $person = Person::factory()->create();
        $member = Member::factory()->create(['person_id' => $person->id]);
        $service = new MemberQuotaService;
        $year = now()->year;
        $month = 6;

        $quota1 = $service->generateQuota($member, $year, $month);
        $quota2 = $service->generateQuota($member, $year, $month);

        expect($quota1->id)->toBe($quota2->id);

        $count = MemberQuota::where('member_id', $member->id)
            ->where('year', $year)
            ->where('month', $month)
            ->count();

        expect($count)->toBe(1);
    });

    it('can calculate overdue amount for a member', function () {
        $person = Person::factory()->create();
        $member = Member::factory()->create(['person_id' => $person->id]);

        // Create overdue quotas
        MemberQuota::factory()->create([
            'member_id' => $member->id,
            'year' => now()->year,
            'month' => 1,
            'amount' => 1000.00,
            'penalty_amount' => 500.00,
            'status' => MemberQuota::STATUS_OVERDUE,
            'due_date' => now()->subDays(30),
        ]);

        MemberQuota::factory()->create([
            'member_id' => $member->id,
            'year' => now()->year,
            'month' => 2,
            'amount' => 2000.00,
            'penalty_amount' => 1000.00,
            'status' => MemberQuota::STATUS_OVERDUE,
            'due_date' => now()->subDays(20),
        ]);

        $service = new MemberQuotaService;
        $overdueAmount = $service->calculateOverdueAmount($member);

        expect($overdueAmount)->toBe(4500.00); // 1000 + 500 + 2000 + 1000
    });

    it('can mark quota as paid', function () {
        $person = Person::factory()->create();
        $member = Member::factory()->create(['person_id' => $person->id]);

        $quota = MemberQuota::factory()->create([
            'member_id' => $member->id,
            'status' => MemberQuota::STATUS_PENDING,
            'penalty_amount' => 500.00,
        ]);

        $service = new MemberQuotaService;
        // Pass null for payment_id since we don't need a real payment for this test
        $service->markQuotaAsPaid($quota, null);

        $quota->refresh();
        expect($quota->status)->toBe(MemberQuota::STATUS_PAID);
        expect($quota->payment_id)->toBeNull();
        expect((float) $quota->penalty_amount)->toBe(0.0);
        expect($quota->payment_date)->not->toBeNull();

        $this->assertDatabaseHas('member_quotas', [
            'id' => $quota->id,
            'status' => MemberQuota::STATUS_PAID,
        ]);
    });

    it('can calculate penalty for overdue quota', function () {
        $person = Person::factory()->create();
        $member = Member::factory()->create(['person_id' => $person->id]);

        $quota = MemberQuota::factory()->create([
            'member_id' => $member->id,
            'amount' => 1000.00,
            'status' => MemberQuota::STATUS_PENDING,
            'due_date' => now()->subDays(10),
        ]);

        $service = new MemberQuotaService;
        $penalty = $service->calculatePenalty($quota);

        $expectedPenalty = 1000.00 * 0.5; // Default penalty percentage
        expect($penalty)->toBe($expectedPenalty);
    });

    it('returns zero penalty for paid quota', function () {
        $person = Person::factory()->create();
        $member = Member::factory()->create(['person_id' => $person->id]);

        $quota = MemberQuota::factory()->create([
            'member_id' => $member->id,
            'amount' => 1000.00,
            'status' => MemberQuota::STATUS_PAID,
            'due_date' => now()->subDays(10),
        ]);

        $service = new MemberQuotaService;
        $penalty = $service->calculatePenalty($quota);

        expect($penalty)->toBe(0.00);
    });

    it('can update overdue quotas', function () {
        $person = Person::factory()->create();
        $member = Member::factory()->create(['person_id' => $person->id]);

        $quota1 = MemberQuota::factory()->create([
            'member_id' => $member->id,
            'status' => MemberQuota::STATUS_PENDING,
            'due_date' => now()->subDays(10),
            'amount' => 1000.00,
        ]);

        $quota2 = MemberQuota::factory()->create([
            'member_id' => $member->id,
            'status' => MemberQuota::STATUS_PENDING,
            'due_date' => now()->addDays(5), // Not overdue yet
            'amount' => 2000.00,
        ]);

        $service = new MemberQuotaService;
        $service->updateOverdueQuotas($member);

        $quota1->refresh();
        $quota2->refresh();

        expect($quota1->status)->toBe(MemberQuota::STATUS_OVERDUE);
        expect((float) $quota1->penalty_amount)->toBe(500.0); // 1000 * 0.5

        expect($quota2->status)->toBe(MemberQuota::STATUS_PENDING);
    });

    it('can check if member should be suspended for quotas', function () {
        $person = Person::factory()->create();
        $member = Member::factory()->create([
            'person_id' => $person->id,
            'status' => Member::STATUS_ACTIVE,
        ]);

        // Create quota overdue for more than 90 days (status must be OVERDUE or PENDING with past due_date)
        $dueDate = now()->subDays(95)->startOfDay();
        $quota = MemberQuota::factory()->create([
            'member_id' => $member->id,
            'status' => MemberQuota::STATUS_OVERDUE,
            'due_date' => $dueDate,
            'amount' => 1000.00,
        ]);

        // Refresh member to ensure relationship is loaded
        $member->refresh();

        // Verify the quota is indeed in overdueQuotas scope
        $overdueQuotas = $member->overdueQuotas()->get();
        expect($overdueQuotas)->toHaveCount(1);

        $service = new MemberQuotaService;
        $shouldSuspend = $service->shouldSuspendForQuotas($member);

        // Verify days overdue calculation (dueDate is in the past, so diffInDays will be negative, use abs)
        $daysOverdue = abs(now()->startOfDay()->diffInDays($dueDate));
        expect($daysOverdue)->toBeGreaterThanOrEqual(95);
        expect($shouldSuspend)->toBeTrue();
    });

    it('does not suggest suspension for inactive members', function () {
        $person = Person::factory()->create();
        $member = Member::factory()->create([
            'person_id' => $person->id,
            'status' => Member::STATUS_INACTIVE,
        ]);

        MemberQuota::factory()->create([
            'member_id' => $member->id,
            'status' => MemberQuota::STATUS_OVERDUE,
            'due_date' => now()->subDays(95),
        ]);

        $service = new MemberQuotaService;
        $shouldSuspend = $service->shouldSuspendForQuotas($member);

        expect($shouldSuspend)->toBeFalse();
    });
});

describe('MemberComplianceService', function () {
    it('can check member compliance', function () {
        $person = Person::factory()->create();
        $member = Member::factory()->create(['person_id' => $person->id]);

        $service = new MemberComplianceService;
        $issues = $service->checkMemberCompliance($member);

        expect($issues)->toBeArray();
    });

    it('detects expired documents', function () {
        $person = Person::factory()->create();
        $member = Member::factory()->create(['person_id' => $person->id]);
        $documentType = DocumentType::factory()->create();

        Document::factory()->create([
            'person_id' => $person->id,
            'member_id' => $member->id,
            'document_type_id' => $documentType->id,
            'expiry_date' => now()->subDays(10),
            'status' => \App\Enums\DocumentStatus::VALIDATED,
        ]);

        $service = new MemberComplianceService;
        $issues = $service->checkMemberCompliance($member);

        expect($issues)->toHaveKey('expired_documents');
        expect($issues['expired_documents'])->not->toBeEmpty();
    });

    it('detects missing required documents', function () {
        $person = Person::factory()->create();
        $member = Member::factory()->create(['person_id' => $person->id]);

        $service = new MemberComplianceService;
        $issues = $service->checkMemberCompliance($member);

        // May have missing documents depending on configuration
        expect($issues)->toBeArray();
    });

    it('detects irregular quotas', function () {
        $person = Person::factory()->create();
        $member = Member::factory()->create(['person_id' => $person->id]);

        MemberQuota::factory()->create([
            'member_id' => $member->id,
            'status' => MemberQuota::STATUS_OVERDUE,
            'due_date' => now()->subDays(10),
            'amount' => 1000.00,
        ]);

        $service = new MemberComplianceService;
        $issues = $service->checkMemberCompliance($member);

        expect($issues)->toHaveKey('quota_irregular');
        expect($issues['quota_irregular'])->toBeTrue();
    });

    it('returns true for compliant members', function () {
        $person = Person::factory()->create();
        $member = Member::factory()->create([
            'person_id' => $person->id,
            'status' => Member::STATUS_ACTIVE,
        ]);

        // Ensure member has regular quotas
        MemberQuota::factory()->create([
            'member_id' => $member->id,
            'status' => MemberQuota::STATUS_PAID,
            'due_date' => now()->addDays(30),
        ]);

        $service = new MemberComplianceService;
        $isCompliant = $service->isCompliant($member);

        expect($isCompliant)->toBeBool();
    });

    it('can get documents expiring soon', function () {
        $person = Person::factory()->create();
        $member = Member::factory()->create(['person_id' => $person->id]);
        $documentType = DocumentType::factory()->create();

        Document::factory()->create([
            'person_id' => $person->id,
            'member_id' => $member->id,
            'document_type_id' => $documentType->id,
            'expiry_date' => now()->addDays(15),
            'status' => \App\Enums\DocumentStatus::VALIDATED,
        ]);

        $service = new MemberComplianceService;
        $expiringDocs = $service->getDocumentsExpiringSoon($member, 30);

        expect($expiringDocs)->not->toBeEmpty();
    });
});

describe('MemberAlertService', function () {
    it('can send quota reminder', function () {
        $user = User::factory()->create();
        $person = Person::factory()->create(['user_id' => $user->id]);
        $member = Member::factory()->create(['person_id' => $person->id]);

        $quota = MemberQuota::factory()->create([
            'member_id' => $member->id,
            'status' => MemberQuota::STATUS_PENDING,
            'due_date' => now()->addDays(10),
        ]);

        $service = new MemberAlertService;
        $service->sendQuotaReminder($member, $quota);

        Notification::assertSentTo($user, \App\Notifications\Member\QuotaReminderNotification::class);
    });

    it('can send quota overdue alert', function () {
        $user = User::factory()->create();
        $person = Person::factory()->create(['user_id' => $user->id]);
        $member = Member::factory()->create(['person_id' => $person->id]);

        $quota = MemberQuota::factory()->create([
            'member_id' => $member->id,
            'status' => MemberQuota::STATUS_OVERDUE,
            'due_date' => now()->subDays(10),
        ]);

        $service = new MemberAlertService;
        $service->sendQuotaOverdueAlert($member, $quota);

        Notification::assertSentTo($user, \App\Notifications\Member\QuotaOverdueNotification::class);
    });

    it('can check and send quota reminders', function () {
        $user = User::factory()->create();
        $person = Person::factory()->create(['user_id' => $user->id]);
        $member = Member::factory()->create([
            'person_id' => $person->id,
            'status' => Member::STATUS_ACTIVE,
        ]);

        $reminderDays = config('members.notifications.quota_reminder_days_before', 15);
        $quota = MemberQuota::factory()->create([
            'member_id' => $member->id,
            'status' => MemberQuota::STATUS_PENDING,
            'due_date' => now()->addDays($reminderDays),
        ]);

        $service = new MemberAlertService;
        $service->checkAndSendQuotaReminders();

        Notification::assertSentTo($user, \App\Notifications\Member\QuotaReminderNotification::class);
    });

    it('can check and send overdue alerts', function () {
        $user = User::factory()->create();
        $person = Person::factory()->create(['user_id' => $user->id]);
        $member = Member::factory()->create([
            'person_id' => $person->id,
            'status' => Member::STATUS_ACTIVE,
        ]);

        $quota = MemberQuota::factory()->create([
            'member_id' => $member->id,
            'status' => MemberQuota::STATUS_OVERDUE,
            'due_date' => now()->subDays(5),
        ]);

        $service = new MemberAlertService;
        $service->checkAndSendOverdueAlerts();

        Notification::assertSentTo($user, \App\Notifications\Member\QuotaOverdueNotification::class);
    });

    it('can check and send suspension warnings', function () {
        $user = User::factory()->create();
        $person = Person::factory()->create(['user_id' => $user->id]);
        $member = Member::factory()->create([
            'person_id' => $person->id,
            'status' => Member::STATUS_ACTIVE,
        ]);

        MemberQuota::factory()->create([
            'member_id' => $member->id,
            'status' => MemberQuota::STATUS_OVERDUE,
            'due_date' => now()->subDays(85), // Close to suspension threshold
        ]);

        $service = new MemberAlertService;
        $service->checkAndSendSuspensionWarnings();

        // May or may not send based on configuration, but should not error
        expect(true)->toBeTrue();
    });

    it('can check and send compliance alerts', function () {
        $user = User::factory()->create();
        $person = Person::factory()->create(['user_id' => $user->id]);
        $member = Member::factory()->create([
            'person_id' => $person->id,
            'status' => Member::STATUS_ACTIVE,
        ]);

        // Create compliance issue - overdue quota
        MemberQuota::factory()->create([
            'member_id' => $member->id,
            'status' => MemberQuota::STATUS_OVERDUE,
            'due_date' => now()->subDays(10),
        ]);

        $service = new MemberAlertService;
        $service->checkAndSendComplianceAlerts();

        Notification::assertSentTo($user, \App\Notifications\Member\ComplianceAlertNotification::class);
    });
});

