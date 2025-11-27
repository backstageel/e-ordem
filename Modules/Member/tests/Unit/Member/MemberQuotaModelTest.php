<?php

namespace Modules\Member\Tests\Unit\Member;

use App\Models\Member;
use App\Models\MemberQuota;
use App\Models\Payment;
use App\Models\Person;
use Carbon\Carbon;

beforeEach(function () {
    // Ensure card type exists for card tests
    \App\Models\CardType::firstOrCreate(
        ['name' => 'Full Member Card'],
        [
            'description' => 'Card for full members',
            'color_code' => '#4CAF50',
            'validity_period_days' => 730,
            'fee' => 500.00,
            'is_active' => true,
        ]
    );

    $this->person = Person::factory()->create();
    $this->member = Member::factory()->create(['person_id' => $this->person->id]);
});

describe('MemberQuota Model', function () {
    it('belongs to a member', function () {
        $quota = MemberQuota::factory()->create(['member_id' => $this->member->id]);

        expect($quota->member)->toBeInstanceOf(Member::class);
        expect($quota->member_id)->toBe($this->member->id);
    });

    it('can belong to a payment', function () {
        $payment = Payment::factory()->create();
        $quota = MemberQuota::factory()->create([
            'member_id' => $this->member->id,
            'payment_id' => $payment->id,
        ]);

        expect($quota->payment)->toBeInstanceOf(Payment::class);
        expect($quota->payment_id)->toBe($payment->id);
    });

    it('can check if quota is overdue', function () {
        $currentYear = now()->year;
        $overdueQuota = MemberQuota::factory()->create([
            'member_id' => $this->member->id,
            'year' => $currentYear,
            'month' => 1,
            'status' => MemberQuota::STATUS_OVERDUE,
            'due_date' => now()->subDays(10),
        ]);

        expect($overdueQuota->isOverdue())->toBeTrue();

        $pendingOverdue = MemberQuota::factory()->create([
            'member_id' => $this->member->id,
            'year' => $currentYear,
            'month' => 2,
            'status' => MemberQuota::STATUS_PENDING,
            'due_date' => now()->subDays(5),
        ]);

        expect($pendingOverdue->isOverdue())->toBeTrue();

        $futureQuota = MemberQuota::factory()->create([
            'member_id' => $this->member->id,
            'year' => $currentYear,
            'month' => 3,
            'status' => MemberQuota::STATUS_PENDING,
            'due_date' => now()->addDays(5),
        ]);

        expect($futureQuota->isOverdue())->toBeFalse();
    });

    it('can check if quota is paid', function () {
        $paidQuota = MemberQuota::factory()->create([
            'member_id' => $this->member->id,
            'status' => MemberQuota::STATUS_PAID,
            'payment_date' => now(),
        ]);

        expect($paidQuota->isPaid())->toBeTrue();

        $unpaidQuota = MemberQuota::factory()->create([
            'member_id' => $this->member->id,
            'status' => MemberQuota::STATUS_PENDING,
            'payment_date' => null,
        ]);

        expect($unpaidQuota->isPaid())->toBeFalse();
    });

    it('has period accessor in Portuguese', function () {
        $quota = MemberQuota::factory()->create([
            'member_id' => $this->member->id,
            'year' => 2024,
            'month' => 1,
        ]);

        expect($quota->period)->toBe('Janeiro 2024');

        $quota2 = MemberQuota::factory()->create([
            'member_id' => $this->member->id,
            'year' => 2024,
            'month' => 6,
        ]);

        expect($quota2->period)->toBe('Junho 2024');
    });

    it('has scope for pending quotas', function () {
        MemberQuota::factory()->create([
            'member_id' => $this->member->id,
            'status' => MemberQuota::STATUS_PENDING,
        ]);
        MemberQuota::factory()->create([
            'member_id' => $this->member->id,
            'status' => MemberQuota::STATUS_PAID,
        ]);

        expect(MemberQuota::pending()->count())->toBe(1);
    });

    it('has scope for overdue quotas', function () {
        MemberQuota::factory()->create([
            'member_id' => $this->member->id,
            'status' => MemberQuota::STATUS_OVERDUE,
            'due_date' => now()->subDays(10),
        ]);
        MemberQuota::factory()->create([
            'member_id' => $this->member->id,
            'status' => MemberQuota::STATUS_PENDING,
            'due_date' => now()->subDays(5),
        ]);
        MemberQuota::factory()->create([
            'member_id' => $this->member->id,
            'status' => MemberQuota::STATUS_PENDING,
            'due_date' => now()->addDays(5),
        ]);

        expect(MemberQuota::overdue()->count())->toBe(2);
    });

    it('has scope for paid quotas', function () {
        MemberQuota::factory()->create([
            'member_id' => $this->member->id,
            'status' => MemberQuota::STATUS_PAID,
        ]);
        MemberQuota::factory()->create([
            'member_id' => $this->member->id,
            'status' => MemberQuota::STATUS_PENDING,
        ]);

        expect(MemberQuota::paid()->count())->toBe(1);
    });
});

