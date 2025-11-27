<?php

use App\Models\Continent;
use App\Models\Country;
use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Registration\Livewire\Wizard\EffectiveWizard;
use Modules\Registration\Livewire\Wizard\Steps\Effective\SelectGradeStep;
use Modules\Registration\Livewire\Wizard\Steps\Effective\VerifyEligibilityStep;
use Tests\TestCase;

uses(TestCase::class);
uses(RefreshDatabase::class);

beforeEach(function () {
    // Use existing data from database
    $this->mozambique = Country::where('code', 'MZ')->orWhere('name', 'Moçambique')->first();

    // If country doesn't exist, create minimal one
    if (! $this->mozambique) {
        $africa = Continent::firstOrCreate(['name' => 'África']);
        $this->mozambique = Country::create([
            'name' => 'Moçambique',
            'iso' => 'MOZ',
            'code' => 'MZ',
            'continent_id' => $africa->id,
        ]);
    }

    // Create a member
    $this->member = Member::factory()->create([
        'registration_number' => 'MEM-2025-0001',
    ]);
});

describe('EffectiveWizard', function () {
    it('can be instantiated', function () {
        $component = Livewire::test(EffectiveWizard::class);

        $component->assertSuccessful();
    });

    it('has correct number of steps', function () {
        $component = Livewire::test(EffectiveWizard::class);

        $wizard = $component->instance();
        expect($wizard->steps())->toHaveCount(4);
    });
});

describe('EffectiveWizard Steps', function () {
    it('has VerifyEligibilityStep as first step', function () {
        $component = Livewire::test(EffectiveWizard::class);

        $wizard = $component->instance();
        $steps = $wizard->steps();

        expect($steps[0])->toBe(VerifyEligibilityStep::class);
    });

    it('has SelectGradeStep as second step', function () {
        $component = Livewire::test(EffectiveWizard::class);

        $wizard = $component->instance();
        $steps = $wizard->steps();

        expect($steps[1])->toBe(SelectGradeStep::class);
    });
});
