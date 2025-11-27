<?php

use App\Enums\RegistrationCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Modules\Registration\Models\RegistrationType;
use Modules\Registration\Services\FeeCalculationService;

uses(Tests\TestCase::class);
uses(RefreshDatabase::class);

beforeEach(function () {
    $this->service = app(FeeCalculationService::class);

    // Seed registration fees
    DB::table('registration_fees')->insert([
        ['code' => 'joia', 'name' => 'Jóia', 'amount' => 3000.00, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ['code' => 'quota_anual', 'name' => 'Quota Anual', 'amount' => 4000.00, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ['code' => 'cartao_ato_inscricao', 'name' => 'Cartão', 'amount' => 300.00, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ['code' => 'certificacao_titulos_estrangeiros', 'name' => 'Tramitação', 'amount' => 2500.00, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ['code' => 'taxa_inscricao_exame', 'name' => 'Taxa Exame', 'amount' => 1000.00, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ['code' => 'autorizacao_provisoria_0_3_meses', 'name' => 'Autorização 0-3 meses', 'amount' => 10000.00, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ['code' => 'autorizacao_provisoria_0_6_meses', 'name' => 'Autorização 0-6 meses', 'amount' => 20000.00, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
    ]);
});

describe('Certification Fee Calculation', function () {
    it('calculates fees for category 1', function () {
        $result = $this->service->calculateForCertification(1);

        expect($result)->toHaveKeys(['fees', 'total', 'before_submission', 'after_approval', 'breakdown']);
        expect($result['total'])->toBe(1000.00); // Only exam fee
        expect($result['before_submission'])->toBe(1000.00);
        expect($result['after_approval'])->toBe(0.00);
    });

    it('calculates fees for category 2', function () {
        $result = $this->service->calculateForCertification(2);

        expect($result['total'])->toBe(3500.00); // Exam + tramitation
        expect($result['before_submission'])->toBe(3500.00);
        expect($result['fees'])->toHaveCount(2); // Exam + tramitation
    });

    it('calculates fees for category 3', function () {
        $result = $this->service->calculateForCertification(3);

        expect($result['total'])->toBe(1000.00); // Only exam fee
        expect($result['before_submission'])->toBe(1000.00);
    });

    it('includes after approval fees when requested', function () {
        $result = $this->service->calculateForCertification(1, true);

        expect($result['total'])->toBe(8300.00); // Exam + joia + quota + cartão
        expect($result['before_submission'])->toBe(1000.00);
        expect($result['after_approval'])->toBe(7300.00);
        expect($result['fees'])->toHaveCount(4); // Exam + joia + quota + cartão
    });

    it('includes after approval fees for category 2 when requested', function () {
        $result = $this->service->calculateForCertification(2, true);

        expect($result['total'])->toBe(10800.00); // Exam + tramitation + joia + quota + cartão
        expect($result['before_submission'])->toBe(3500.00);
        expect($result['after_approval'])->toBe(7300.00);
    });
});

describe('Provisional Fee Calculation', function () {
    it('calculates fees for subtype 1', function () {
        $result = $this->service->calculateForProvisional(1);

        expect($result)->toHaveKeys(['fees', 'total', 'before_submission', 'after_approval', 'breakdown']);
        expect($result['before_submission'])->toBe(3500.00); // Tramitation + exam
        expect($result['after_approval'])->toBe(7300.00); // Joia + quota + cartão
        expect($result['total'])->toBe(10800.00);
    });

    it('calculates fees for subtype 3 (0-3 months)', function () {
        $result = $this->service->calculateForProvisional(3);

        expect($result['before_submission'])->toBe(2500.00); // Only tramitation
        expect($result['after_approval'])->toBe(10000.00); // Authorization 0-3 months
        expect($result['total'])->toBe(12500.00);
    });

    it('calculates fees for subtype 6 (0-6 months)', function () {
        $result = $this->service->calculateForProvisional(6);

        expect($result['before_submission'])->toBe(3500.00); // Tramitation + exam
        expect($result['after_approval'])->toBe(27300.00); // Authorization 0-6 months (20000) + joia (3000) + quota (4000) + cartão (300)
        expect($result['total'])->toBe(30800.00);
    });

    it('calculates fees for subtype 8 (0-6 months, no exam)', function () {
        $result = $this->service->calculateForProvisional(8);

        expect($result['before_submission'])->toBe(2500.00); // Only tramitation
        expect($result['after_approval'])->toBe(20000.00); // Authorization 0-6 months
        expect($result['total'])->toBe(22500.00);
    });

    it('calculates fees for subtype 10 (no tramitation)', function () {
        $result = $this->service->calculateForProvisional(10);

        expect($result['before_submission'])->toBe(1000.00); // Only exam
        expect($result['after_approval'])->toBe(7300.00); // Joia + quota + cartão
        expect($result['total'])->toBe(8300.00);
    });

    it('calculates fees for subtype 4 (exempt from common requirements)', function () {
        $result = $this->service->calculateForProvisional(4);

        expect((float) $result['before_submission'])->toBe(0.0); // No tramitation
        expect($result['after_approval'])->toBe(10000.00); // Authorization 0-3 months
        expect($result['total'])->toBe(10000.00);
    });

    it('returns empty for invalid subtype', function () {
        $result = $this->service->calculateForProvisional(99);

        expect($result['fees'])->toBe([]);
        expect($result['total'])->toBe(0.00);
    });
});

describe('Effective Fee Calculation', function () {
    it('calculates fees for grade A', function () {
        $result = $this->service->calculateForEffective('A');

        expect($result)->toHaveKeys(['fees', 'total', 'before_submission', 'after_approval', 'breakdown']);
        expect($result['total'])->toBe(7300.00); // Joia + quota + cartão
        expect($result['before_submission'])->toBe(7300.00);
        expect($result['after_approval'])->toBe(0.00);
        expect($result['fees'])->toHaveCount(3);
    });

    it('calculates fees for grade B', function () {
        $result = $this->service->calculateForEffective('B');

        expect($result['total'])->toBe(7300.00);
    });

    it('calculates fees for grade C', function () {
        $result = $this->service->calculateForEffective('C');

        expect($result['total'])->toBe(7300.00);
    });
});

describe('Calculate For Type', function () {
    it('calculates fees for certification type', function () {
        $type = RegistrationType::factory()->create([
            'category' => RegistrationCategory::PROVISIONAL, // Temporary category, but code identifies it as certification
            'category_number' => 1,
            'code' => 'CERT-1', // Code starting with CERT- identifies it as certification
        ]);

        $result = $this->service->calculateForType($type);

        expect($result)->toHaveKeys(['fees', 'total', 'breakdown']);
        expect($result['total'])->toBe(1000.00);
    });

    it('calculates fees for provisional type', function () {
        $type = RegistrationType::factory()->create([
            'category' => RegistrationCategory::PROVISIONAL,
            'subtype_number' => 1,
            'code' => 'PROV-01',
        ]);

        $result = $this->service->calculateForType($type);

        expect($result)->toHaveKeys(['fees', 'total', 'breakdown']);
        expect($result['total'])->toBe(10800.00);
    });

    it('calculates fees for effective type', function () {
        $type = RegistrationType::factory()->create([
            'category' => RegistrationCategory::EFFECTIVE,
            'grade' => 'A',
            'code' => 'EFET-A',
        ]);

        $result = $this->service->calculateForType($type);

        expect($result)->toHaveKeys(['fees', 'total', 'breakdown']);
        expect($result['total'])->toBe(7300.00);
    });

    it('returns empty for unknown category', function () {
        $type = RegistrationType::factory()->create([
            'category' => RegistrationCategory::RENEWAL, // Use a valid enum value that doesn't match certification/provisional/effective
            'code' => 'RENEW-1', // Not a certification code
        ]);

        $result = $this->service->calculateForType($type);

        expect($result['fees'])->toBe([]);
        expect($result['total'])->toBe(0.00);
    });
});
