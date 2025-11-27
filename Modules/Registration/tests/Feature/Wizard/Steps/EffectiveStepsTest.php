<?php

use App\Models\Continent;
use App\Models\Country;
use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Registration\Livewire\Wizard\EffectiveWizard;
use Modules\Registration\Livewire\Wizard\Steps\Effective\ReviewSubmitStep;
use Modules\Registration\Livewire\Wizard\Steps\Effective\UploadDocumentsStep;
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

describe('Effective Steps', function () {
    it('UploadDocumentsStep exists in EffectiveWizard', function () {
        $component = Livewire::test(EffectiveWizard::class);

        $wizard = $component->instance();
        $steps = $wizard->steps();

        expect($steps)->toContain(UploadDocumentsStep::class);
    });

    it('ReviewSubmitStep exists in EffectiveWizard', function () {
        $component = Livewire::test(EffectiveWizard::class);

        $wizard = $component->instance();
        $steps = $wizard->steps();

        expect($steps)->toContain(ReviewSubmitStep::class);
    });
});
