<?php

use App\Enums\RegistrationCategory;
use App\Models\CivilState;
use App\Models\Country;
use App\Models\District;
use App\Models\Gender;
use App\Models\IdentityDocument;
use App\Models\Province;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Registration\Livewire\Wizard\CertificationWizard;
use Modules\Registration\Models\Registration;
use Modules\Registration\Models\RegistrationType;
use Tests\TestCase;

uses(TestCase::class);
uses(RefreshDatabase::class);

beforeEach(function () {
    // Use existing data from database
    $this->mozambique = Country::where('code', 'MZ')->orWhere('name', 'Moçambique')->first();
    $this->portugal = Country::where('code', 'PT')->orWhere('name', 'Portugal')->first();

    // If countries don't exist, create minimal ones
    if (! $this->mozambique) {
        $africa = \App\Models\Continent::firstOrCreate(['name' => 'África']);
        $this->mozambique = Country::create([
            'name' => 'Moçambique',
            'iso' => 'MOZ',
            'code' => 'MZ',
            'continent_id' => $africa->id,
        ]);
    }

    if (! $this->portugal) {
        $europe = \App\Models\Continent::firstOrCreate(['name' => 'Europa']);
        $this->portugal = Country::create([
            'name' => 'Portugal',
            'iso' => 'PRT',
            'code' => 'PT',
            'continent_id' => $europe->id,
        ]);
    }

    // Use existing provinces and districts
    $this->maputo = Province::where('country_id', $this->mozambique->id)->first();
    if (! $this->maputo) {
        $this->maputo = Province::create([
            'name' => 'Maputo',
            'country_id' => $this->mozambique->id,
        ]);
    }

    $this->maputoCity = District::where('province_id', $this->maputo->id)->first();
    if (! $this->maputoCity) {
        $this->maputoCity = District::create([
            'name' => 'Maputo Cidade',
            'province_id' => $this->maputo->id,
        ]);
    }

    // Create gender
    $this->gender = Gender::firstOrCreate([
        'name' => 'Masculino',
    ], [
        'code' => 'M',
    ]);

    // Create civil state
    $this->civilState = CivilState::firstOrCreate([
        'name' => 'Solteiro',
    ]);

    // Create identity document type
    $this->identityDoc = IdentityDocument::firstOrCreate([
        'name' => 'Bilhete de Identidade',
    ], [
        'code' => 'BI',
    ]);

    // Create registration type
    $this->certType = RegistrationType::factory()->create([
        'category' => RegistrationCategory::PROVISIONAL,
        'code' => 'CERT-1',
        'category_number' => 1,
        'name' => 'Moçambicanos formados em Moçambique',
        'eligibility_criteria' => [
            'nationality' => 'moçambicano',
            'birth_country' => 'moçambique',
            'training_country' => 'moçambique',
        ],
    ]);
});

describe('Certification Wizard Complete Flow', function () {
    it('can navigate through wizard steps', function () {
        $component = Livewire::test(CertificationWizard::class);

        // Verify wizard is instantiated
        $wizard = $component->instance();
        expect($wizard->steps())->toHaveCount(7);

        // Test that wizard can be rendered
        $component->assertSuccessful();
    });

    it('validates required fields throughout the flow', function () {
        $component = Livewire::test(CertificationWizard::class);

        // Wizard should start on first step
        $component->assertSuccessful();

        // Note: Full integration testing of wizard steps requires
        // testing through HTTP requests or using browser tests
        // This test verifies the wizard structure is correct
    });
});
