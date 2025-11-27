<?php

namespace Modules\Member\Tests\Unit\Member;

use App\Models\Member;
use App\Models\MemberCard;
use App\Models\MemberQuota;
use App\Models\MemberStatusHistory;
use App\Models\Person;
use App\Enums\DocumentStatus;

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

describe('Member Model Relationships', function () {
    it('belongs to a person', function () {
        expect($this->member->person)->toBeInstanceOf(Person::class);
        expect($this->member->person_id)->toBe($this->person->id);
    });

    it('has many registrations', function () {
        \Modules\Registration\Models\Registration::factory()->count(3)->create(['person_id' => $this->person->id]);

        expect($this->member->registrations)->toHaveCount(3);
    });

    it('has many documents', function () {
        \App\Models\Document::factory()->count(2)->create(['member_id' => $this->member->id]);

        expect($this->member->documents)->toHaveCount(2);
    });

    it('has one latest card', function () {
        MemberCard::factory()->create(['member_id' => $this->member->id, 'created_at' => now()->subDay()]);
        $latestCard = MemberCard::factory()->create(['member_id' => $this->member->id]);

        expect($this->member->card)->toBeInstanceOf(MemberCard::class);
        expect($this->member->card->id)->toBe($latestCard->id);
    });

    it('has many cards', function () {
        MemberCard::factory()->count(3)->create(['member_id' => $this->member->id]);

        expect($this->member->cards)->toHaveCount(3);
    });

    it('has many quota history', function () {
        // Create quotas with unique year/month combinations to avoid duplicate key violations
        for ($i = 1; $i <= 5; $i++) {
            MemberQuota::factory()->create([
                'member_id' => $this->member->id,
                'year' => now()->year,
                'month' => $i,
            ]);
        }

        expect($this->member->quotaHistory)->toHaveCount(5);
    });

    it('has many status history', function () {
        MemberStatusHistory::factory()->count(3)->create(['member_id' => $this->member->id]);

        expect($this->member->statusHistory)->toHaveCount(3);
    });

    it('belongs to many medical specialities', function () {
        $speciality = \App\Models\MedicalSpeciality::factory()->create();
        $this->member->medicalSpecialities()->attach($speciality->id, ['is_primary' => true]);
        $this->member->refresh();

        expect($this->member->medicalSpecialities)->toHaveCount(1);
        expect($this->member->medicalSpecialities->first())->toBeInstanceOf(\App\Models\MedicalSpeciality::class);
        expect($this->member->medicalSpecialities->first()->id)->toBe($speciality->id);
    });
});

describe('Member Model Attributes', function () {
    it('has full_name accessor', function () {
        expect($this->member->full_name)->toBe($this->person->full_name);
    });

    it('has nome accessor (Portuguese)', function () {
        expect($this->member->nome)->toBe($this->member->full_name);
    });
});

describe('Member Model Queries', function () {
    it('can get active registrations', function () {
        \Modules\Registration\Models\Registration::factory()->create([
            'person_id' => $this->person->id,
            'status' => \App\Enums\RegistrationStatus::APPROVED,
        ]);
        \Modules\Registration\Models\Registration::factory()->create([
            'person_id' => $this->person->id,
            'status' => \App\Enums\RegistrationStatus::SUBMITTED,
        ]);

        expect($this->member->activeRegistrations()->count())->toBe(1);
    });

    it('can get pending registrations', function () {
        \Modules\Registration\Models\Registration::factory()->create([
            'person_id' => $this->person->id,
            'status' => \App\Enums\RegistrationStatus::SUBMITTED,
        ]);
        \Modules\Registration\Models\Registration::factory()->create([
            'person_id' => $this->person->id,
            'status' => \App\Enums\RegistrationStatus::APPROVED,
        ]);

        expect($this->member->pendingRegistrations()->count())->toBe(1);
    });

    it('can get pending quotas', function () {
        MemberQuota::factory()->create([
            'member_id' => $this->member->id,
            'status' => MemberQuota::STATUS_PENDING,
        ]);
        MemberQuota::factory()->create([
            'member_id' => $this->member->id,
            'status' => MemberQuota::STATUS_PAID,
        ]);

        expect($this->member->pendingQuotas()->count())->toBe(1);
    });

    it('can get overdue quotas', function () {
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

        expect($this->member->overdueQuotas()->count())->toBe(2); // Both overdue and pending with past due date
    });
});

describe('Member Model Business Logic', function () {
    it('can check if quotas are regular', function () {
        $member = Member::factory()->create([
            'person_id' => $this->person->id,
            'dues_paid' => true,
            'dues_paid_until' => now()->addYear(),
        ]);

        expect($member->isQuotaRegular())->toBeTrue();
    });

    it('detects irregular quotas when overdue', function () {
        MemberQuota::factory()->create([
            'member_id' => $this->member->id,
            'status' => MemberQuota::STATUS_OVERDUE,
            'due_date' => now()->subDays(10),
        ]);

        expect($this->member->isQuotaRegular())->toBeFalse();
    });

    it('can check if has pending documents', function () {
        \App\Models\Document::factory()->create([
            'member_id' => $this->member->id,
            'status' => DocumentStatus::PENDING,
        ]);

        expect($this->member->hasPendingDocuments())->toBeTrue();
    });

    it('can check if can generate card', function () {
        $member = Member::factory()->create([
            'person_id' => $this->person->id,
            'status' => Member::STATUS_ACTIVE,
            'dues_paid' => true,
            'dues_paid_until' => now()->addYear(),
        ]);

        expect($member->canGenerateCard())->toBeTrue();
    });

    it('cannot generate card if suspended', function () {
        $member = Member::factory()->create([
            'person_id' => $this->person->id,
            'status' => Member::STATUS_SUSPENDED,
        ]);

        expect($member->canGenerateCard())->toBeFalse();
    });

    it('cannot generate card if has pending documents', function () {
        $member = Member::factory()->create([
            'person_id' => $this->person->id,
            'status' => Member::STATUS_ACTIVE,
            'dues_paid' => true,
        ]);

        \App\Models\Document::factory()->create([
            'member_id' => $member->id,
            'status' => DocumentStatus::PENDING,
        ]);

        expect($member->canGenerateCard())->toBeFalse();
    });

    it('can get quota status', function () {
        // Regular status
        $member1 = Member::factory()->create([
            'person_id' => Person::factory()->create()->id,
            'dues_paid' => true,
        ]);

        expect($member1->getQuotaStatus())->toBe('regular');

        // Irregular status
        MemberQuota::factory()->create([
            'member_id' => $this->member->id,
            'status' => MemberQuota::STATUS_OVERDUE,
            'due_date' => now()->subDays(10),
        ]);

        expect($this->member->getQuotaStatus())->toBe('irregular');
    });

    it('can calculate total quota due', function () {
        MemberQuota::factory()->create([
            'member_id' => $this->member->id,
            'status' => MemberQuota::STATUS_OVERDUE,
            'amount' => 1000.00,
            'penalty_amount' => 500.00,
            'due_date' => now()->subDays(10),
        ]);

        MemberQuota::factory()->create([
            'member_id' => $this->member->id,
            'status' => MemberQuota::STATUS_OVERDUE,
            'amount' => 2000.00,
            'penalty_amount' => 1000.00,
            'due_date' => now()->subDays(5),
        ]);

        expect($this->member->totalQuotaDue())->toBe(4500.00); // 1000 + 500 + 2000 + 1000
    });
});

