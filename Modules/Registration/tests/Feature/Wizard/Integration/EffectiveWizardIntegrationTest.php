<?php

use App\Models\Exam;
use App\Models\ExamApplication;
use App\Models\ExamResult;
use App\Models\Member;
use App\Models\Person;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Registration\Livewire\Wizard\EffectiveWizard;
use Tests\TestCase;

uses(TestCase::class);
uses(RefreshDatabase::class);

beforeEach(function () {
    // Create user, person, and member relationship
    $user = User::factory()->create();
    $person = Person::factory()->create(['user_id' => $user->id]);
    $this->member = Member::factory()->create([
        'person_id' => $person->id,
        'registration_number' => 'MEM-2025-0001',
    ]);

    // Create exam with all required fields
    $exam = Exam::factory()->create([
        'type' => 'teorico',
        'specialty' => 'Medicina Geral',
        'duration' => 120,
        'location' => 'Maputo',
        'capacity' => 100,
    ]);

    // Create exam application with all required fields
    $examApplication = ExamApplication::create([
        'exam_id' => $exam->id,
        'user_id' => $user->id,
        'exam_type' => 'certificacao',
        'specialty' => 'Medicina Geral',
        'status' => 'approved',
    ]);

    // Create exam result linked to application
    $this->examResult = ExamResult::create([
        'exam_application_id' => $examApplication->id,
        'status' => 'presente',
        'decision' => 'aprovado',
        'grade' => 15.5,
    ]);
});

describe('Effective Wizard Complete Flow', function () {
    it('can navigate through wizard steps', function () {
        $component = Livewire::test(EffectiveWizard::class);

        // Verify wizard is instantiated
        $wizard = $component->instance();
        expect($wizard->steps())->toHaveCount(4);

        // Test that wizard can be rendered
        $component->assertSuccessful();
    });

    it('validates wizard structure', function () {
        $component = Livewire::test(EffectiveWizard::class);

        $wizard = $component->instance();
        $steps = $wizard->steps();

        // Verify steps exist
        expect($steps)->toBeArray();
        expect($steps)->toHaveCount(4);
    });
});
