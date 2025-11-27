<?php

use App\Enums\RegistrationCategory;
use App\Models\Continent;
use App\Models\Country;
use App\Models\Gender;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Registration\Livewire\Wizard\CertificationWizard;
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
        $africa = Continent::firstOrCreate(['name' => 'África']);
        $this->mozambique = Country::create([
            'name' => 'Moçambique',
            'iso' => 'MOZ',
            'code' => 'MZ',
            'continent_id' => $africa->id,
        ]);
    }

    if (! $this->portugal) {
        $europe = Continent::firstOrCreate(['name' => 'Europa']);
        $this->portugal = Country::create([
            'name' => 'Portugal',
            'iso' => 'PRT',
            'code' => 'PT',
            'continent_id' => $europe->id,
        ]);
    }

    // Create gender
    $this->gender = Gender::firstOrCreate([
        'name' => 'Masculino',
    ], [
        'code' => 'M',
    ]);

    // Create registration types for certification
    $this->certType1 = RegistrationType::factory()->create([
        'category' => RegistrationCategory::PROVISIONAL, // Temporary, but code identifies it
        'code' => 'CERT-1',
        'category_number' => 1,
        'name' => 'Moçambicanos formados em Moçambique',
        'eligibility_criteria' => [
            'nationality' => 'moçambicano',
            'birth_country' => 'moçambique',
            'training_country' => 'moçambique',
        ],
    ]);

    $this->certType2 = RegistrationType::factory()->create([
        'category' => RegistrationCategory::PROVISIONAL,
        'code' => 'CERT-2',
        'category_number' => 2,
        'name' => 'Moçambicanos formados no estrangeiro',
        'eligibility_criteria' => [
            'nationality' => 'moçambicano',
            'birth_country' => 'moçambique',
            'training_country' => 'estrangeiro',
        ],
    ]);

    $this->certType3 = RegistrationType::factory()->create([
        'category' => RegistrationCategory::PROVISIONAL,
        'code' => 'CERT-3',
        'category_number' => 3,
        'name' => 'Estrangeiros formados em Moçambique',
        'eligibility_criteria' => [
            'nationality' => 'estrangeiro',
            'training_country' => 'moçambique',
        ],
    ]);
});

describe('CertificationWizard', function () {
    it('can be instantiated', function () {
        $component = Livewire::test(CertificationWizard::class);

        $component->assertSuccessful();
    });

    it('has correct number of steps', function () {
        $component = Livewire::test(CertificationWizard::class);

        $wizard = $component->instance();
        expect($wizard->steps())->toHaveCount(7);
    });

    it('has correct step order', function () {
        $component = Livewire::test(CertificationWizard::class);

        $wizard = $component->instance();
        $steps = $wizard->steps();

        // Verify first step is ChooseCategoryStep
        expect($steps[0])->toBe(\Modules\Registration\Livewire\Wizard\Steps\Certification\ChooseCategoryStep::class);
    });
});
