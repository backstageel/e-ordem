<?php

use App\Enums\RegistrationCategory;
use App\Enums\RegistrationSubtype;
use App\Models\Country;
use App\Models\District;
use App\Models\Province;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Registration\Livewire\Wizard\ProvisionalWizard;
use Modules\Registration\Models\RegistrationType;
use Tests\TestCase;

uses(TestCase::class);
uses(RefreshDatabase::class);

beforeEach(function () {
    // Use existing data from database
    $this->mozambique = Country::where('code', 'MZ')->orWhere('name', 'Moçambique')->first();
    if (! $this->mozambique) {
        $africa = \App\Models\Continent::firstOrCreate(['name' => 'África']);
        $this->mozambique = Country::create([
            'name' => 'Moçambique',
            'iso' => 'MOZ',
            'code' => 'MZ',
            'continent_id' => $africa->id,
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

    // Create registration types for provisional subtypes
    foreach (RegistrationSubtype::cases() as $subtype) {
        RegistrationType::factory()->create([
            'category' => RegistrationCategory::PROVISIONAL,
            'code' => 'PROV-'.$subtype->value,
            'subtype_number' => $subtype->value,
            'name' => $subtype->label(),
        ]);
    }
});

describe('Provisional Wizard Complete Flow', function () {
    it('can navigate through wizard steps', function () {
        $component = Livewire::test(ProvisionalWizard::class);

        // Verify wizard is instantiated
        $wizard = $component->instance();
        expect($wizard->steps())->toHaveCount(7);

        // Test that wizard can be rendered
        $component->assertSuccessful();
    });

    it('validates wizard structure', function () {
        $component = Livewire::test(ProvisionalWizard::class);

        $wizard = $component->instance();
        $steps = $wizard->steps();

        // Verify steps exist
        expect($steps)->toBeArray();
        expect($steps)->toHaveCount(7);
    });
});
