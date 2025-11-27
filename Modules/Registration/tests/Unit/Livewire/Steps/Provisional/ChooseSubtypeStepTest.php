<?php

use App\Enums\RegistrationCategory;
use App\Enums\RegistrationSubtype;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Registration\Livewire\Wizard\Steps\Provisional\ChooseSubtypeStep;
use Modules\Registration\Models\RegistrationType;
use Tests\TestCase;

uses(TestCase::class);
uses(RefreshDatabase::class);

beforeEach(function () {
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

describe('ChooseSubtypeStep - Unit Tests', function () {
    it('returns all provisional subtypes', function () {
        $step = new ChooseSubtypeStep;

        $subtypes = $step->getSubtypesProperty();

        expect($subtypes)->toBeArray();
        expect($subtypes)->toHaveCount(count(RegistrationSubtype::cases()));
    });

    it('has correct subtype structure', function () {
        $step = new ChooseSubtypeStep;

        $subtypes = $step->getSubtypesProperty();

        foreach ($subtypes as $subtype) {
            expect($subtype)->toHaveKey('value');
            expect($subtype)->toHaveKey('label');
            expect($subtype)->toHaveKey('duration_days');
            expect($subtype)->toHaveKey('is_renewable');
        }
    });
});
