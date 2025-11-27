<?php

use App\Enums\RegistrationCategory;
use App\Models\Continent;
use App\Models\Country;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Registration\Models\RegistrationType;
use Modules\Registration\Services\EligibilityValidationService;

uses(Tests\TestCase::class);
uses(RefreshDatabase::class);

beforeEach(function () {
    $this->service = app(EligibilityValidationService::class);

    // Create continent for countries
    $africa = Continent::firstOrCreate(['name' => 'África']);
    $europe = Continent::firstOrCreate(['name' => 'Europa']);

    // Create Mozambique country
    $this->mozambique = Country::factory()->create([
        'name' => 'Moçambique',
        'iso' => 'MOZ',
        'code' => 'MZ',
        'continent_id' => $africa->id,
    ]);

    // Create foreign country
    $this->foreignCountry = Country::factory()->create([
        'name' => 'Portugal',
        'iso' => 'PRT',
        'code' => 'PT',
        'continent_id' => $europe->id,
    ]);
});

describe('Certification Validation', function () {
    it('validates category 1 - mozambican trained in mozambique', function () {
        $type = RegistrationType::factory()->create([
            'category' => RegistrationCategory::PROVISIONAL, // Temporary category, but code identifies it as certification
            'category_number' => 1,
            'code' => 'CERT-1', // Code starting with CERT- identifies it as certification
            'eligibility_criteria' => [
                'nationality' => 'moçambicano',
                'training_country' => 'moçambique',
            ],
        ]);

        $data = [
            'nationality_id' => $this->mozambique->id,
            'birth_country_id' => $this->mozambique->id,
            'birth_date' => now()->subYears(25)->format('Y-m-d'),
            'training_country_id' => $this->mozambique->id,
        ];

        $result = $this->service->validateForCertification($data, 1);
        expect($result['eligible'])->toBeTrue();
    });

    it('rejects category 1 if not mozambican', function () {
        $type = RegistrationType::factory()->create([
            'category' => RegistrationCategory::PROVISIONAL, // Temporary category, but code identifies it as certification
            'category_number' => 1,
            'code' => 'CERT-1',
            'eligibility_criteria' => [
                'nationality' => 'moçambicano',
            ],
        ]);

        $data = [
            'nationality_id' => $this->foreignCountry->id,
            'birth_date' => now()->subYears(25)->format('Y-m-d'),
        ];

        $result = $this->service->validateForCertification($data, 1);
        expect($result['eligible'])->toBeFalse();
        expect($result['issues'])->toContain('Categoria requer nacionalidade moçambicana.');
    });

    it('rejects category 1 if trained abroad', function () {
        $type = RegistrationType::factory()->create([
            'category' => RegistrationCategory::PROVISIONAL, // Temporary category, but code identifies it as certification
            'category_number' => 1,
            'code' => 'CERT-1',
            'eligibility_criteria' => [
                'nationality' => 'moçambicano',
                'training_country' => 'moçambique',
            ],
        ]);

        $data = [
            'nationality_id' => $this->mozambique->id,
            'birth_date' => now()->subYears(25)->format('Y-m-d'),
            'training_country_id' => $this->foreignCountry->id,
        ];

        $result = $this->service->validateForCertification($data, 1);
        expect($result['eligible'])->toBeFalse();
        expect($result['issues'])->toContain('Categoria 1 requer formação em instituições moçambicanas.');
    });

    it('validates category 2 - mozambican trained abroad', function () {
        $type = RegistrationType::factory()->create([
            'category' => RegistrationCategory::PROVISIONAL, // Temporary category, but code identifies it as certification
            'category_number' => 2,
            'code' => 'CERT-2',
            'eligibility_criteria' => [
                'nationality' => 'moçambicano',
                'training_country' => 'estrangeiro',
            ],
        ]);

        $data = [
            'nationality_id' => $this->mozambique->id,
            'birth_date' => now()->subYears(25)->format('Y-m-d'),
            'training_country_id' => $this->foreignCountry->id,
        ];

        $result = $this->service->validateForCertification($data, 2);
        expect($result['eligible'])->toBeTrue();
    });

    it('rejects category 2 if trained in mozambique', function () {
        $type = RegistrationType::factory()->create([
            'category' => RegistrationCategory::PROVISIONAL, // Temporary category, but code identifies it as certification
            'category_number' => 2,
            'code' => 'CERT-2',
            'eligibility_criteria' => [
                'nationality' => 'moçambicano',
                'training_country' => 'estrangeiro',
            ],
        ]);

        $data = [
            'nationality_id' => $this->mozambique->id,
            'birth_date' => now()->subYears(25)->format('Y-m-d'),
            'training_country_id' => $this->mozambique->id,
        ];

        $result = $this->service->validateForCertification($data, 2);
        expect($result['eligible'])->toBeFalse();
        expect($result['issues'])->toContain('Categoria 2 requer formação em instituições estrangeiras.');
    });

    it('validates category 3 - foreign trained in mozambique', function () {
        $type = RegistrationType::factory()->create([
            'category' => RegistrationCategory::PROVISIONAL, // Temporary category, but code identifies it as certification
            'category_number' => 3,
            'code' => 'CERT-3',
        ]);

        $data = [
            'nationality_id' => $this->foreignCountry->id,
            'birth_date' => now()->subYears(25)->format('Y-m-d'),
            'training_country_id' => $this->mozambique->id,
        ];

        $result = $this->service->validateForCertification($data, 3);
        expect($result['eligible'])->toBeTrue();
    });

    it('rejects category 3 if mozambican', function () {
        $type = RegistrationType::factory()->create([
            'category' => RegistrationCategory::PROVISIONAL, // Temporary category, but code identifies it as certification
            'category_number' => 3,
            'code' => 'CERT-3',
            'eligibility_criteria' => [
                'nationality' => 'estrangeiro', // Category 3 is for foreigners
            ],
        ]);

        $data = [
            'nationality_id' => $this->mozambique->id,
            'birth_date' => now()->subYears(25)->format('Y-m-d'),
        ];

        $result = $this->service->validateForCertification($data, 3);
        expect($result['eligible'])->toBeFalse();
        expect($result['issues'])->toContain('Categoria 3 é exclusiva para médicos estrangeiros.');
    });

    it('rejects if age is less than 22', function () {
        $type = RegistrationType::factory()->create([
            'category' => RegistrationCategory::PROVISIONAL, // Temporary category, but code identifies it as certification
            'category_number' => 1,
            'code' => 'CERT-1',
        ]);

        $data = [
            'nationality_id' => $this->mozambique->id,
            'birth_date' => now()->subYears(20)->format('Y-m-d'),
        ];

        $result = $this->service->validateForCertification($data, 1);
        expect($result['eligible'])->toBeFalse();
        expect($result['issues'])->toContain('Idade mínima de 22 anos é obrigatória para inscrição.');
    });

    it('returns invalid if registration type not found', function () {
        $data = [
            'nationality_id' => $this->mozambique->id,
        ];

        $result = $this->service->validateForCertification($data, 99);
        expect($result['eligible'])->toBeFalse();
        expect($result['issues'])->toContain('Tipo de inscrição não encontrado.');
    });
});

describe('Provisional Validation', function () {
    it('validates foreign doctor for provisional', function () {
        $type = RegistrationType::factory()->create([
            'category' => RegistrationCategory::PROVISIONAL,
            'subtype_number' => 1,
            'code' => 'PROV-01',
        ]);

        $data = [
            'nationality_id' => $this->foreignCountry->id,
            'birth_date' => now()->subYears(25)->format('Y-m-d'),
        ];

        $result = $this->service->validateForProvisional($data, 1);
        expect($result['eligible'])->toBeTrue();
    });

    it('rejects mozambican for provisional', function () {
        $type = RegistrationType::factory()->create([
            'category' => RegistrationCategory::PROVISIONAL,
            'subtype_number' => 1,
            'code' => 'PROV-01',
        ]);

        $data = [
            'nationality_id' => $this->mozambique->id,
            'birth_date' => now()->subYears(25)->format('Y-m-d'),
        ];

        $result = $this->service->validateForProvisional($data, 1);
        expect($result['eligible'])->toBeFalse();
        expect($result['issues'])->toContain('Inscrições provisórias são exclusivas para médicos estrangeiros. Moçambicanos devem usar Pré-inscrição para Certificação ou Inscrição Efetiva.');
    });

    it('rejects if age is less than 22', function () {
        $type = RegistrationType::factory()->create([
            'category' => RegistrationCategory::PROVISIONAL,
            'subtype_number' => 1,
            'code' => 'PROV-01',
        ]);

        $data = [
            'nationality_id' => $this->foreignCountry->id,
            'birth_date' => now()->subYears(20)->format('Y-m-d'),
        ];

        $result = $this->service->validateForProvisional($data, 1);
        expect($result['eligible'])->toBeFalse();
        expect($result['issues'])->toContain('Idade mínima de 22 anos é obrigatória para inscrição.');
    });
});

describe('Effective Validation', function () {
    it('validates mozambican with exam result for effective', function () {
        $data = [
            'nationality_id' => $this->mozambique->id,
            'exam_result_id' => '123',
        ];

        $result = $this->service->validateForEffective($data, 'A');
        expect($result['eligible'])->toBeTrue();
    });

    it('rejects foreign for effective', function () {
        $data = [
            'nationality_id' => $this->foreignCountry->id,
            'exam_result_id' => '123',
        ];

        $result = $this->service->validateForEffective($data, 'A');
        expect($result['eligible'])->toBeFalse();
        expect($result['issues'])->toContain('Inscrições efetivas são exclusivas para médicos moçambicanos.');
    });

    it('rejects if exam result is missing', function () {
        $data = [
            'nationality_id' => $this->mozambique->id,
        ];

        $result = $this->service->validateForEffective($data, 'A');
        expect($result['eligible'])->toBeFalse();
        expect($result['issues'])->toContain('É necessário ter resultado de exame aprovado para inscrição efetiva.');
    });
});

describe('Get Eligibility Issues', function () {
    it('gets eligibility issues for certification type', function () {
        $type = RegistrationType::factory()->create([
            'category' => RegistrationCategory::PROVISIONAL, // Temporary category, but code identifies it as certification
            'category_number' => 1,
            'code' => 'CERT-1',
        ]);

        $data = [
            'nationality_id' => $this->mozambique->id,
            'birth_date' => now()->subYears(25)->format('Y-m-d'),
        ];

        $result = $this->service->getEligibilityIssues($data, $type);
        expect($result)->toHaveKey('eligible');
        expect($result)->toHaveKey('issues');
    });

    it('gets eligibility issues for provisional type', function () {
        $type = RegistrationType::factory()->create([
            'category' => RegistrationCategory::PROVISIONAL,
            'subtype_number' => 1,
            'code' => 'PROV-01',
        ]);

        $data = [
            'nationality_id' => $this->foreignCountry->id,
            'birth_date' => now()->subYears(25)->format('Y-m-d'),
        ];

        $result = $this->service->getEligibilityIssues($data, $type);
        expect($result)->toHaveKey('eligible');
        expect($result)->toHaveKey('issues');
    });

    it('gets eligibility issues for effective type', function () {
        $type = RegistrationType::factory()->create([
            'category' => RegistrationCategory::EFFECTIVE,
            'grade' => 'A',
            'code' => 'EFET-A',
        ]);

        $data = [
            'nationality_id' => $this->mozambique->id,
            'exam_result_id' => '123',
        ];

        $result = $this->service->getEligibilityIssues($data, $type);
        expect($result)->toHaveKey('eligible');
        expect($result)->toHaveKey('issues');
    });
});
