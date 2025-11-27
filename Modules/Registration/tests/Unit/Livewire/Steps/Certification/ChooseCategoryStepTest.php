<?php

use App\Enums\RegistrationCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Registration\Livewire\Wizard\Steps\Certification\ChooseCategoryStep;
use Modules\Registration\Models\RegistrationType;
use Tests\TestCase;

uses(TestCase::class);
uses(RefreshDatabase::class);

beforeEach(function () {
    // Create registration types for certification
    RegistrationType::factory()->create([
        'category' => RegistrationCategory::PROVISIONAL,
        'code' => 'CERT-1',
        'category_number' => 1,
        'name' => 'Moçambicanos formados em Moçambique',
    ]);

    RegistrationType::factory()->create([
        'category' => RegistrationCategory::PROVISIONAL,
        'code' => 'CERT-2',
        'category_number' => 2,
        'name' => 'Moçambicanos formados no estrangeiro',
    ]);

    RegistrationType::factory()->create([
        'category' => RegistrationCategory::PROVISIONAL,
        'code' => 'CERT-3',
        'category_number' => 3,
        'name' => 'Estrangeiros formados em Moçambique',
    ]);
});

describe('ChooseCategoryStep - Unit Tests', function () {
    it('returns all three categories', function () {
        $step = new ChooseCategoryStep;

        $categories = $step->getCategoriesProperty();

        expect($categories)->toBeArray();
        expect($categories)->toHaveCount(3);
        expect($categories[1]['code'])->toBe('CERT-1');
        expect($categories[2]['code'])->toBe('CERT-2');
        expect($categories[3]['code'])->toBe('CERT-3');
    });

    it('has correct category structure', function () {
        $step = new ChooseCategoryStep;

        $categories = $step->getCategoriesProperty();

        foreach ($categories as $category) {
            expect($category)->toHaveKey('code');
            expect($category)->toHaveKey('name');
            expect($category)->toHaveKey('description');
            expect($category)->toHaveKey('documents_count');
            expect($category)->toHaveKey('fee');
        }
    });
});
