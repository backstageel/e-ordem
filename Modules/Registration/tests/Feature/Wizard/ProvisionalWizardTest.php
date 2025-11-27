<?php

use App\Enums\RegistrationCategory;
use App\Enums\RegistrationSubtype;
use App\Models\Continent;
use App\Models\Country;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Registration\Livewire\Wizard\ProvisionalWizard;
use Modules\Registration\Livewire\Wizard\Steps\Provisional\ChooseSubtypeStep;
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

describe('ProvisionalWizard', function () {
    it('can be instantiated', function () {
        $component = Livewire::test(ProvisionalWizard::class);

        $component->assertSuccessful();
    });

    it('has correct number of steps', function () {
        $component = Livewire::test(ProvisionalWizard::class);

        $wizard = $component->instance();
        expect($wizard->steps())->toHaveCount(7);
    });
});

describe('ProvisionalWizard Steps', function () {
    it('has ChooseSubtypeStep as first step', function () {
        $component = Livewire::test(ProvisionalWizard::class);

        $wizard = $component->instance();
        $steps = $wizard->steps();

        expect($steps[0])->toBe(ChooseSubtypeStep::class);
    });
});
