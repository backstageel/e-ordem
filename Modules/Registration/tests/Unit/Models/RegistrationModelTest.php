<?php

use App\Enums\RegistrationCategory;
use App\Enums\RegistrationStatus;
use App\Models\Document;
use App\Models\Member;
use App\Models\Payment;
use App\Models\Person;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Registration\Models\Registration;
use Modules\Registration\Models\RegistrationType;

uses(Tests\TestCase::class);
uses(RefreshDatabase::class);

beforeEach(function () {
    $this->registrationType = RegistrationType::factory()->create([
        'category' => RegistrationCategory::PROVISIONAL,
        'code' => 'PROV-01',
    ]);

    $this->person = Person::factory()->create();

    $this->registration = Registration::factory()->create([
        'person_id' => $this->person->id,
        'registration_type_id' => $this->registrationType->id,
        'status' => RegistrationStatus::SUBMITTED,
    ]);
});

describe('Relationships', function () {
    it('belongs to a person', function () {
        expect($this->registration->person)->toBeInstanceOf(Person::class);
        expect($this->registration->person_id)->toBe($this->person->id);
    });

    it('belongs to a registration type', function () {
        expect($this->registration->registrationType)->toBeInstanceOf(RegistrationType::class);
        expect($this->registration->registration_type_id)->toBe($this->registrationType->id);
    });

    it('belongs to a member', function () {
        $member = Member::factory()->create();
        $this->registration->update(['member_id' => $member->id]);

        expect($this->registration->member)->toBeInstanceOf(Member::class);
        expect($this->registration->member_id)->toBe($member->id);
    });

    it('belongs to approved by user', function () {
        $user = User::factory()->create();
        $this->registration->update([
            'status' => RegistrationStatus::APPROVED,
            'approved_by' => $user->id,
        ]);

        expect($this->registration->approvedBy)->toBeInstanceOf(User::class);
        expect($this->registration->approved_by)->toBe($user->id);
    });

    it('has many documents', function () {
        Document::factory()->count(3)->create([
            'registration_id' => $this->registration->id,
        ]);

        expect($this->registration->documents)->toHaveCount(3);
        expect($this->registration->documents->first())->toBeInstanceOf(Document::class);
    });

    it('has many payments', function () {
        Payment::factory()->count(2)->create([
            'payable_id' => $this->registration->id,
            'payable_type' => Registration::class,
        ]);

        expect($this->registration->payments)->toHaveCount(2);
        expect($this->registration->payments->first())->toBeInstanceOf(Payment::class);
    });

    it('has one workflow', function () {
        $workflow = \Modules\Registration\Models\RegistrationWorkflow::factory()->create([
            'registration_id' => $this->registration->id,
        ]);

        expect($this->registration->workflow)->toBeInstanceOf(\Modules\Registration\Models\RegistrationWorkflow::class);
        expect($this->registration->workflow->id)->toBe($workflow->id);
    });

    it('belongs to previous registration for renewals', function () {
        $previous = Registration::factory()->create();
        $this->registration->update(['previous_registration_id' => $previous->id]);

        expect($this->registration->previousRegistration)->toBeInstanceOf(Registration::class);
        expect($this->registration->previous_registration_id)->toBe($previous->id);
    });

    it('has many renewals', function () {
        Registration::factory()->count(2)->create([
            'previous_registration_id' => $this->registration->id,
        ]);

        expect($this->registration->renewals)->toHaveCount(2);
    });
});

describe('Type Checks', function () {
    it('checks if registration is certification', function () {
        $this->registration->update(['type' => 'certification']);
        expect($this->registration->isCertification())->toBeTrue();

        $this->registration->update(['type' => 'provisional']);
        expect($this->registration->isCertification())->toBeFalse();
    });

    it('checks if registration is provisional', function () {
        $this->registration->update(['type' => 'provisional']);
        expect($this->registration->isProvisional())->toBeTrue();

        $this->registration->update(['type' => 'certification']);
        expect($this->registration->isProvisional())->toBeFalse();
    });

    it('checks if registration is effective', function () {
        $this->registration->update(['type' => 'effective']);
        expect($this->registration->isEffective())->toBeTrue();

        $this->registration->update(['type' => 'provisional']);
        expect($this->registration->isEffective())->toBeFalse();
    });
});

describe('Status Checks', function () {
    it('checks if registration is approved', function () {
        $this->registration->update(['status' => RegistrationStatus::APPROVED]);
        expect($this->registration->isApproved())->toBeTrue();
        expect($this->registration->isPending())->toBeFalse();
    });

    it('checks if registration is pending', function () {
        $this->registration->update(['status' => RegistrationStatus::SUBMITTED]);
        expect($this->registration->isPending())->toBeTrue();
    });

    it('checks if registration is rejected', function () {
        $this->registration->update(['status' => RegistrationStatus::REJECTED]);
        expect($this->registration->isRejected())->toBeTrue();
    });

    it('checks if registration is validated', function () {
        $this->registration->update(['status' => RegistrationStatus::VALIDATED]);
        expect($this->registration->isValidated())->toBeTrue();
    });

    it('checks if registration is under review', function () {
        $this->registration->update(['status' => RegistrationStatus::UNDER_REVIEW]);
        expect($this->registration->isUnderReview())->toBeTrue();
    });

    it('checks if registration has documents pending', function () {
        $this->registration->update(['status' => RegistrationStatus::DOCUMENTS_PENDING]);
        expect($this->registration->hasDocumentsPending())->toBeTrue();
    });

    it('checks if registration has payment pending', function () {
        $this->registration->update(['status' => RegistrationStatus::PAYMENT_PENDING]);
        expect($this->registration->hasPaymentPending())->toBeTrue();
    });

    it('checks if registration is archived', function () {
        $this->registration->update(['status' => RegistrationStatus::ARCHIVED]);
        expect($this->registration->isArchived())->toBeTrue();
    });

    it('checks if registration is active', function () {
        $this->registration->update([
            'status' => RegistrationStatus::APPROVED,
            'expiry_date' => now()->addYear(),
        ]);
        expect($this->registration->isActive())->toBeTrue();

        $this->registration->update(['expiry_date' => now()->subDay()]);
        expect($this->registration->isActive())->toBeFalse();
    });

    it('checks if registration is expired', function () {
        $this->registration->update(['expiry_date' => now()->subDay()]);
        expect($this->registration->isExpired())->toBeTrue();

        $this->registration->update(['expiry_date' => now()->addDay()]);
        expect($this->registration->isExpired())->toBeFalse();
    });

    it('checks if registration is renewable', function () {
        $this->registration->update([
            'status' => RegistrationStatus::APPROVED,
            'expiry_date' => now()->addDays(15),
        ]);
        expect($this->registration->isRenewable())->toBeTrue();

        $this->registration->update(['expiry_date' => now()->addDays(60)]);
        expect($this->registration->isRenewable())->toBeFalse();
    });
});

describe('Scopes', function () {
    it('filters pending registrations', function () {
        Registration::factory()->create(['status' => RegistrationStatus::SUBMITTED]);
        Registration::factory()->create(['status' => RegistrationStatus::APPROVED]);

        expect(Registration::pending()->count())->toBe(1);
    });

    it('filters approved registrations', function () {
        Registration::factory()->create(['status' => RegistrationStatus::APPROVED]);
        Registration::factory()->create(['status' => RegistrationStatus::REJECTED]);

        expect(Registration::approved()->count())->toBe(1);
    });

    it('filters rejected registrations', function () {
        Registration::factory()->create(['status' => RegistrationStatus::REJECTED]);
        Registration::factory()->create(['status' => RegistrationStatus::APPROVED]);

        expect(Registration::rejected()->count())->toBe(1);
    });

    it('filters expired registrations', function () {
        Registration::factory()->create([
            'status' => RegistrationStatus::APPROVED,
            'expiry_date' => now()->subDay(),
        ]);
        Registration::factory()->create([
            'status' => RegistrationStatus::APPROVED,
            'expiry_date' => now()->addDay(),
        ]);

        expect(Registration::expired()->count())->toBe(1);
    });

    it('filters active registrations', function () {
        Registration::factory()->create([
            'status' => RegistrationStatus::APPROVED,
            'expiry_date' => now()->addDay(),
        ]);
        Registration::factory()->create([
            'status' => RegistrationStatus::APPROVED,
            'expiry_date' => now()->subDay(),
        ]);

        expect(Registration::active()->count())->toBe(1);
    });

    it('filters under review registrations', function () {
        Registration::factory()->create(['status' => RegistrationStatus::UNDER_REVIEW]);
        Registration::factory()->create(['status' => RegistrationStatus::SUBMITTED]);

        expect(Registration::underReview()->count())->toBe(1);
    });

    it('filters documents pending registrations', function () {
        Registration::factory()->create(['status' => RegistrationStatus::DOCUMENTS_PENDING]);
        Registration::factory()->create(['status' => RegistrationStatus::SUBMITTED]);

        expect(Registration::documentsPending()->count())->toBe(1);
    });

    it('filters payment pending registrations', function () {
        Registration::factory()->create(['status' => RegistrationStatus::PAYMENT_PENDING]);
        Registration::factory()->create(['status' => RegistrationStatus::SUBMITTED]);

        expect(Registration::paymentPending()->count())->toBe(1);
    });

    it('filters archived registrations', function () {
        Registration::factory()->create(['status' => RegistrationStatus::ARCHIVED]);
        Registration::factory()->create(['status' => RegistrationStatus::SUBMITTED]);

        expect(Registration::archived()->count())->toBe(1);
    });

    it('filters certification registrations', function () {
        Registration::factory()->create(['type' => 'certification']);
        Registration::factory()->create(['type' => 'provisional']);

        expect(Registration::certification()->count())->toBe(1);
    });

    it('filters provisional registrations', function () {
        $provisionalType = RegistrationType::factory()->create([
            'category' => RegistrationCategory::PROVISIONAL,
        ]);
        Registration::factory()->create([
            'type' => 'provisional',
            'registration_type_id' => $provisionalType->id,
        ]);
        Registration::factory()->create(['type' => 'certification']);

        expect(Registration::provisional()->count())->toBeGreaterThanOrEqual(1);
    });

    it('filters effective registrations', function () {
        $effectiveType = RegistrationType::factory()->create([
            'category' => RegistrationCategory::EFFECTIVE,
        ]);
        Registration::factory()->create([
            'type' => 'effective',
            'registration_type_id' => $effectiveType->id,
        ]);
        Registration::factory()->create(['type' => 'certification']);

        expect(Registration::effective()->count())->toBeGreaterThanOrEqual(1);
    });
});

describe('Process Number Generation', function () {
    it('generates certification process number', function () {
        $registration = Registration::factory()->create([
            'type' => 'certification',
            'category' => 1,
        ]);

        $processNumber = $registration->generateProcessNumber();
        expect($processNumber)->toStartWith('CERT-1-');
    });

    it('generates provisional process number', function () {
        $registration = Registration::factory()->create([
            'type' => 'provisional',
            'subtype' => 5,
        ]);

        $processNumber = $registration->generateProcessNumber();
        expect($processNumber)->toStartWith('PROV-05-');
    });

    it('generates effective process number', function () {
        $registration = Registration::factory()->create([
            'type' => 'effective',
            'grade' => 'A',
        ]);

        $processNumber = $registration->generateProcessNumber();
        expect($processNumber)->toStartWith('EFET-A-');
    });
});

describe('Required Documents', function () {
    it('gets required documents from registration type', function () {
        $type = RegistrationType::factory()->create([
            'required_documents' => ['doc1', 'doc2'],
        ]);
        $registration = Registration::factory()->create([
            'registration_type_id' => $type->id,
        ]);

        $documents = $registration->getRequiredDocuments();
        expect($documents)->toContain('doc1', 'doc2');
    });

    it('merges common and specific documents for provisional', function () {
        $type = RegistrationType::factory()->create([
            'category' => RegistrationCategory::PROVISIONAL,
            'required_documents' => [
                'common' => ['common1', 'common2'],
                'specific' => ['specific1'],
            ],
        ]);
        $registration = Registration::factory()->create([
            'type' => 'provisional',
            'registration_type_id' => $type->id,
        ]);

        $documents = $registration->getRequiredDocuments();
        expect($documents)->toContain('common1', 'common2', 'specific1');
    });
});

describe('Status Labels and Colors', function () {
    it('gets status label', function () {
        $this->registration->update(['status' => RegistrationStatus::APPROVED]);
        expect($this->registration->getStatusLabel())->toBeString();
    });

    it('gets status badge color', function () {
        $this->registration->update(['status' => RegistrationStatus::APPROVED]);
        expect($this->registration->getStatusBadgeColor())->toBeString();
    });
});
