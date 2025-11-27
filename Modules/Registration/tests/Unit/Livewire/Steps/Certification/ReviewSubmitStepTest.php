<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Registration\Livewire\Wizard\Steps\Certification\ReviewSubmitStep;
use Modules\Registration\Models\RegistrationType;
use Modules\Registration\Services\FeeCalculationService;
use Tests\TestCase;

uses(TestCase::class);
uses(RefreshDatabase::class);

beforeEach(function () {
    // Create registration fees in database
    \DB::table('registration_fees')->insert([
        ['code' => 'taxa_inscricao_exame', 'name' => 'Taxa de Inscrição no Exame', 'amount' => 5000.00, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ['code' => 'joia', 'name' => 'Jóia', 'amount' => 3000.00, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ['code' => 'quota_anual', 'name' => 'Quota Anual', 'amount' => 3000.00, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ['code' => 'cartao_ato_inscricao', 'name' => 'Cartão no Ato da Inscrição', 'amount' => 300.00, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
    ]);

    // Create registration type for certification
    $this->certType = RegistrationType::factory()->create([
        'code' => 'CERT-1',
        'category_number' => 1,
        'name' => 'Moçambicanos formados em Moçambique',
    ]);
});

describe('ReviewSubmitStep - Unit Tests', function () {
    it('calculates fees correctly for category 1', function () {
        $feeService = app(FeeCalculationService::class);
        // Use calculateForCertification directly with category number
        $fees = $feeService->calculateForCertification(1);

        expect($fees)->toBeArray();
        expect($fees)->toHaveKey('fees');
        expect($fees)->toHaveKey('total');
        expect($fees)->toHaveKey('breakdown');
        expect($fees['total'])->toBeGreaterThan(0);
    });

    it('has submit method', function () {
        $step = new ReviewSubmitStep;

        expect(method_exists($step, 'submit'))->toBeTrue();
    });
});
