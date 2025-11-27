<?php

use App\Enums\RegistrationCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Registration\Models\Registration;
use Modules\Registration\Models\RegistrationType;

uses(Tests\TestCase::class);
uses(RefreshDatabase::class);

beforeEach(function () {
    $this->registrationType = RegistrationType::factory()->create([
        'category' => RegistrationCategory::PROVISIONAL,
        'code' => 'PROV-01',
        'renewable' => true,
        'max_renewals' => 2,
    ]);
});

describe('Relationships', function () {
    it('has many registrations', function () {
        Registration::factory()->count(3)->create([
            'registration_type_id' => $this->registrationType->id,
        ]);

        expect($this->registrationType->registrations)->toHaveCount(3);
        expect($this->registrationType->registrations->first())->toBeInstanceOf(Registration::class);
    });
});

describe('Scopes', function () {
    it('filters provisional registration types', function () {
        RegistrationType::factory()->create(['category' => RegistrationCategory::PROVISIONAL]);
        RegistrationType::factory()->create(['category' => RegistrationCategory::EFFECTIVE]);

        expect(RegistrationType::provisional()->count())->toBeGreaterThanOrEqual(1);
    });

    it('filters effective registration types', function () {
        RegistrationType::factory()->create(['category' => RegistrationCategory::EFFECTIVE]);
        RegistrationType::factory()->create(['category' => RegistrationCategory::PROVISIONAL]);

        expect(RegistrationType::effective()->count())->toBeGreaterThanOrEqual(1);
    });

    it('filters certification registration types', function () {
        RegistrationType::factory()->create(['code' => 'CERT-01']);
        RegistrationType::factory()->create(['code' => 'PROV-01']);

        expect(RegistrationType::certification()->count())->toBe(1);
    });

    it('filters active registration types', function () {
        RegistrationType::factory()->create(['is_active' => true]);
        RegistrationType::factory()->create(['is_active' => false]);

        expect(RegistrationType::active()->count())->toBeGreaterThanOrEqual(1);
    });

    it('filters by category', function () {
        RegistrationType::factory()->create(['category' => RegistrationCategory::PROVISIONAL]);
        RegistrationType::factory()->create(['category' => RegistrationCategory::EFFECTIVE]);

        $provisionalCount = RegistrationType::byCategory(RegistrationCategory::PROVISIONAL->value)->count();
        expect($provisionalCount)->toBeGreaterThanOrEqual(1);
    });

    it('filters renewal registration types', function () {
        RegistrationType::factory()->create(['name' => 'Renewal Type']);
        RegistrationType::factory()->create(['name' => 'Regular Type']);

        expect(RegistrationType::renewal()->count())->toBe(1);
    });

    it('filters reinstatement registration types', function () {
        RegistrationType::factory()->create(['name' => 'Reinstatement Type']);
        RegistrationType::factory()->create(['name' => 'Regular Type']);

        expect(RegistrationType::reinstatement()->count())->toBe(1);
    });
});

describe('Type Checks', function () {
    it('checks if registration type is provisional', function () {
        $type = RegistrationType::factory()->create([
            'category' => RegistrationCategory::PROVISIONAL,
        ]);
        expect($type->isProvisional())->toBeTrue();

        $type = RegistrationType::factory()->create([
            'category' => RegistrationCategory::EFFECTIVE,
        ]);
        expect($type->isProvisional())->toBeFalse();
    });

    it('checks if registration type is effective', function () {
        $type = RegistrationType::factory()->create([
            'category' => RegistrationCategory::EFFECTIVE,
        ]);
        expect($type->isEffective())->toBeTrue();

        $type = RegistrationType::factory()->create([
            'category' => RegistrationCategory::PROVISIONAL,
        ]);
        expect($type->isEffective())->toBeFalse();
    });

    it('checks if registration type is certification', function () {
        $type = RegistrationType::factory()->create(['code' => 'CERT-01']);
        expect($type->isCertification())->toBeTrue();

        $type = RegistrationType::factory()->create(['code' => 'PROV-01']);
        expect($type->isCertification())->toBeFalse();
    });

    it('checks if registration type is renewable', function () {
        $type = RegistrationType::factory()->create([
            'renewable' => true,
            'max_renewals' => 2,
        ]);
        expect($type->isRenewable())->toBeTrue();

        $type = RegistrationType::factory()->create([
            'renewable' => false,
            'max_renewals' => 0,
        ]);
        expect($type->isRenewable())->toBeFalse();
    });
});

describe('Documents', function () {
    it('gets required documents', function () {
        $type = RegistrationType::factory()->create([
            'required_documents' => ['doc1', 'doc2', 'doc3'],
        ]);

        expect($type->getRequiredDocuments())->toBe(['doc1', 'doc2', 'doc3']);
    });

    it('gets common documents for provisional', function () {
        $type = RegistrationType::factory()->create([
            'category' => RegistrationCategory::PROVISIONAL,
            'required_documents' => [
                'common' => ['common1', 'common2'],
                'specific' => ['specific1'],
            ],
        ]);

        expect($type->getCommonDocuments())->toBe(['common1', 'common2']);
    });

    it('returns empty array for common documents if not provisional', function () {
        $type = RegistrationType::factory()->create([
            'category' => RegistrationCategory::EFFECTIVE,
            'required_documents' => [
                'common' => ['common1'],
            ],
        ]);

        expect($type->getCommonDocuments())->toBe([]);
    });

    it('gets specific documents by subtype', function () {
        $type = RegistrationType::factory()->create([
            'category' => RegistrationCategory::PROVISIONAL,
            'subtype_number' => 5,
            'required_documents' => [
                'common' => ['common1'],
                'specific' => [
                    'subtype_5' => ['specific1', 'specific2'],
                    'subtype_6' => ['specific3'],
                ],
            ],
        ]);

        expect($type->getSpecificDocuments())->toBe(['specific1', 'specific2']);
    });

    it('gets all required documents (common + specific)', function () {
        $type = RegistrationType::factory()->create([
            'category' => RegistrationCategory::PROVISIONAL,
            'subtype_number' => 5,
            'required_documents' => [
                'common' => ['common1', 'common2'],
                'specific' => [
                    'subtype_5' => ['specific1'],
                ],
            ],
        ]);

        $allDocs = $type->getAllRequiredDocuments();
        expect($allDocs)->toContain('common1', 'common2', 'specific1');
    });

    it('checks if document is required', function () {
        $type = RegistrationType::factory()->create([
            'required_documents' => ['doc1', 'doc2'],
        ]);

        expect($type->requiresDocument('doc1'))->toBeTrue();
        expect($type->requiresDocument('doc3'))->toBeFalse();
    });
});

describe('Eligibility Criteria', function () {
    it('gets eligibility criteria', function () {
        $type = RegistrationType::factory()->create([
            'eligibility_criteria' => [
                'nationality' => 'moçambicano',
                'age' => 22,
            ],
        ]);

        expect($type->getEligibilityCriteria())->toBe([
            'nationality' => 'moçambicano',
            'age' => 22,
        ]);
    });
});

describe('Workflow Steps', function () {
    it('gets workflow steps', function () {
        $type = RegistrationType::factory()->create([
            'workflow_steps' => [1, 2, 3, 4, 5],
        ]);

        expect($type->getWorkflowSteps())->toBe([1, 2, 3, 4, 5]);
    });
});

describe('Category Label', function () {
    it('gets category label', function () {
        $type = RegistrationType::factory()->create([
            'category' => RegistrationCategory::PROVISIONAL,
        ]);

        expect($type->getCategoryLabel())->toBeString();
    });
});
