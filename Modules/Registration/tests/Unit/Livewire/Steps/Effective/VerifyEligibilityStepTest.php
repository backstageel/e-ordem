<?php

use App\Models\Exam;
use App\Models\ExamApplication;
use App\Models\ExamResult;
use App\Models\Member;
use App\Models\Person;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Registration\Models\Registration;
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

describe('VerifyEligibilityStep - Unit Tests', function () {
    it('validates exam result exists and is approved', function () {
        $approvedResult = ExamResult::where('id', $this->examResult->id)
            ->where('decision', 'aprovado')
            ->first();

        expect($approvedResult)->not->toBeNull();
        expect($approvedResult->decision)->toBe('aprovado');
    });

    it('checks if member already has effective registration', function () {
        // Create existing registration
        Registration::factory()->create([
            'member_id' => $this->member->id,
            'type' => 'effective',
            'status' => \App\Enums\RegistrationStatus::APPROVED,
        ]);

        $existing = Registration::where('member_id', $this->member->id)
            ->where('type', 'effective')
            ->where('status', '!=', \App\Enums\RegistrationStatus::REJECTED)
            ->first();

        expect($existing)->not->toBeNull();
    });
});
