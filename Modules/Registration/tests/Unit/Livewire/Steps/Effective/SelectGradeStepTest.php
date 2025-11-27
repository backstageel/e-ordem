<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Registration\Livewire\Wizard\Steps\Effective\SelectGradeStep;
use Tests\TestCase;

uses(TestCase::class);
uses(RefreshDatabase::class);

describe('SelectGradeStep - Unit Tests', function () {
    it('returns available grades', function () {
        $step = new SelectGradeStep;

        $grades = $step->getGradesProperty();

        expect($grades)->toBeArray();
        expect($grades)->toHaveKey('A');
        expect($grades)->toHaveKey('B');
        expect($grades)->toHaveKey('C');
    });

    it('has correct grade structure', function () {
        $step = new SelectGradeStep;

        $grades = $step->getGradesProperty();

        foreach ($grades as $grade) {
            expect($grade)->toHaveKey('code');
            expect($grade)->toHaveKey('name');
            expect($grade)->toHaveKey('description');
            expect($grade)->toHaveKey('subgrades');
        }
    });
});
