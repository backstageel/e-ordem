<?php

use App\Enums\RegistrationCategory;
use App\Models\CivilState;
use App\Models\Country;
use App\Models\Gender;
use App\Models\Province;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Registration\Livewire\Wizard\Steps\Certification\PersonalInfoStep;
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

    // Use existing province
    $this->maputo = Province::where('country_id', $this->mozambique->id)->first();
    if (! $this->maputo) {
        $this->maputo = Province::create([
            'name' => 'Maputo',
            'country_id' => $this->mozambique->id,
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

describe('PersonalInfoStep - Unit Tests', function () {
    it('has correct validation rules structure', function () {
        $step = new PersonalInfoStep;

        // Test that rules method exists
        expect(method_exists($step, 'rules'))->toBeTrue();

        // Test validation by calling saveAndNext with invalid data
        $step->form = [];
        try {
            $step->saveAndNext();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->errors();
            expect($errors)->toHaveKey('form.first_name');
            expect($errors)->toHaveKey('form.last_name');
            expect($errors)->toHaveKey('form.birth_date');
            expect($errors)->toHaveKey('form.gender_id');
        }
    });

    it('filters provinces based on country', function () {
        $step = new PersonalInfoStep;
        $step->form = ['birth_country_id' => $this->mozambique->id];

        $provinces = $step->getBirthProvincesProperty();

        expect($provinces)->toBeInstanceOf(\Illuminate\Database\Eloquent\Collection::class);
    });

    it('resets province when country changes', function () {
        $step = new PersonalInfoStep;
        $step->form = [
            'birth_country_id' => $this->mozambique->id,
            'birth_province_id' => $this->maputo->id,
            'birth_district_id' => 1,
        ];

        $step->form['birth_country_id'] = $this->portugal->id;
        $step->updatedFormBirthCountryId();

        expect($step->form['birth_province_id'])->toBeNull();
        expect($step->form['birth_district_id'])->toBeNull();
    });
});
