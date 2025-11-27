<?php

namespace Modules\Exam\Tests\Feature\Exam;

use App\Actions\Exam\CreateExamAction;
use App\Actions\Exam\ProcessResultsAction;
use App\Actions\Exam\SubmitApplicationAction;
use App\Data\Exam\ExamApplicationData;
use App\Data\Exam\ExamData;
use App\Models\Exam;
use App\Models\ExamApplication;
use App\Models\ExamResult;
use App\Models\User;
use App\Services\Exam\ExamEligibilityService;
use App\Services\Exam\ExamResultService;
use Spatie\Permission\Models\Role;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    // Create roles
    Role::firstOrCreate(['name' => 'admin']);
    Role::firstOrCreate(['name' => 'member']);
    Role::firstOrCreate(['name' => 'teacher']);
    Role::firstOrCreate(['name' => 'evaluator']);

    // Create a user for authentication
    $this->adminUser = User::factory()->create([
        'two_factor_enabled' => false,
        'email_verified_at' => now(),
    ]);
    $this->adminUser->assignRole('admin');

    // Create member user
    $this->memberUser = User::factory()->create([
        'two_factor_enabled' => false,
        'email_verified_at' => now(),
    ]);
    $this->memberUser->assignRole('member');

    // Create an evaluator user
    $this->evaluator = User::factory()->create();
    $this->evaluator->assignRole('evaluator');
});

it('can create an exam using CreateExamAction', function () {
    $action = app(CreateExamAction::class);

    $examData = ExamData::from([
        'name' => 'Test Exam',
        'type' => 'teorico',
        'level' => 'intermediario',
        'specialty' => 'Cardiology',
        'description' => 'Test description',
        'exam_date' => now()->addMonth()->format('Y-m-d'),
        'start_time' => '09:00',
        'end_time' => '12:00',
        'location' => 'Test Location',
        'address' => 'Test Address',
        'capacity' => 50,
        'minimum_grade' => 10,
        'questions_count' => 100,
        'time_limit' => 180,
        'attempts_allowed' => 1,
        'allow_consultation' => false,
        'is_mandatory' => true,
        'immediate_result' => false,
        'primary_evaluator_id' => $this->evaluator->id,
        'notes' => 'Test notes',
        'status' => 'draft',
    ]);

    $exam = $action->execute($examData);

    expect($exam)->toBeInstanceOf(Exam::class)
        ->and($exam->name)->toBe('Test Exam')
        ->and($exam->specialty)->toBe('Cardiology')
        ->and($exam->status)->toBe('draft');
});

it('can submit exam application using SubmitApplicationAction', function () {
    // Use existing continent from States (ID 1 = 'África')
    $continent = \App\Models\Continent::find(1);
    expect($continent)->not->toBeNull('Continent should exist from States');

    // Use existing country from States (ID 148 = Moçambique)
    $country = \App\Models\Country::where('iso', 'MOZ')->first();
    expect($country)->not->toBeNull('Country should exist from States');

    // Create person for member user (Person belongs to User, not the other way)
    $person = \App\Models\Person::factory()->create([
        'user_id' => $this->memberUser->id,
        'nationality_id' => $country->id,
    ]);

    // Create academic qualification to pass eligibility check
    \App\Models\AcademicQualification::create([
        'person_id' => $person->id,
        'qualification_type' => 'licenciatura',
        'field_of_study' => 'Medicine',
        'completion_date' => now()->subYears(2)->format('Y-m-d'),
        'institution_name' => 'Test University',
    ]);

    $exam = Exam::create([
        'name' => 'Test Exam',
        'type' => 'teorico',
        'specialty' => 'Cardiology',
        'exam_date' => now()->addMonth(),
        'start_time' => '09:00',
        'end_time' => '12:00',
        'location' => 'Test Location',
        'capacity' => 50,
        'minimum_grade' => 10,
        'status' => 'scheduled',
        'payment_required' => false,
        'duration' => 180,
    ]);

    $action = app(SubmitApplicationAction::class);

    $applicationData = ExamApplicationData::from([
        'exam_id' => $exam->id,
        'user_id' => $this->memberUser->id,
        'exam_type' => 'certificacao',
        'specialty' => $exam->specialty,
        'terms_accepted' => true,
    ]);

    $application = $action->execute($applicationData);

    expect($application)->toBeInstanceOf(ExamApplication::class)
        ->and($application->exam_id)->toBe($exam->id)
        ->and($application->user_id)->toBe($this->memberUser->id)
        ->and($application->status)->toBe('submitted');
});

it('can process exam results using ProcessResultsAction', function () {
    $exam = Exam::create([
        'name' => 'Test Exam',
        'type' => 'teorico',
        'specialty' => 'Cardiology',
        'exam_date' => now()->addMonth(),
        'start_time' => '09:00',
        'end_time' => '12:00',
        'location' => 'Test Location',
        'capacity' => 50,
        'minimum_grade' => 10,
        'status' => 'scheduled',
        'duration' => 180,
    ]);

    $application = ExamApplication::create([
        'exam_id' => $exam->id,
        'user_id' => $this->memberUser->id,
        'exam_type' => 'certificacao',
        'specialty' => 'Cardiology',
        'status' => 'approved',
    ]);

    $action = app(ProcessResultsAction::class);

    $results = [
        [
            'application_id' => $application->id,
            'grade' => 15,
            'status' => 'presente',
            'decision_type' => 'aprovacao_automatica',
        ],
    ];

    $processed = $action->execute($exam, $results);

    expect($processed)->toHaveKey('statistics')
        ->and($exam->fresh()->status)->toBe('completed')
        ->and(ExamResult::where('exam_application_id', $application->id)->exists())->toBeTrue();
});

it('can generate exam statistics using ExamResultService', function () {
    $exam = Exam::create([
        'name' => 'Test Exam',
        'type' => 'teorico',
        'specialty' => 'Cardiology',
        'exam_date' => now()->addMonth(),
        'start_time' => '09:00',
        'end_time' => '12:00',
        'location' => 'Test Location',
        'capacity' => 50,
        'minimum_grade' => 10,
        'status' => 'scheduled',
        'duration' => 180,
    ]);

    $application1 = ExamApplication::create([
        'exam_id' => $exam->id,
        'user_id' => $this->memberUser->id,
        'exam_type' => 'certificacao',
        'specialty' => 'Cardiology',
        'status' => 'approved',
    ]);

    $application2 = ExamApplication::create([
        'exam_id' => $exam->id,
        'user_id' => $this->adminUser->id,
        'exam_type' => 'certificacao',
        'specialty' => 'Cardiology',
        'status' => 'approved',
    ]);

    ExamResult::create([
        'exam_application_id' => $application1->id,
        'grade' => 15,
        'status' => 'presente',
        'decision' => 'aprovado',
    ]);

    ExamResult::create([
        'exam_application_id' => $application2->id,
        'grade' => 8,
        'status' => 'presente',
        'decision' => 'reprovado',
    ]);

    $service = app(ExamResultService::class);
    $statistics = $service->getStatistics($exam);

    expect($statistics)->toHaveKey('total_applications')
        ->and($statistics)->toHaveKey('approved')
        ->and($statistics)->toHaveKey('rejected');
});

it('can validate eligibility using ExamEligibilityService', function () {
    $exam = Exam::create([
        'name' => 'Test Exam',
        'type' => 'teorico',
        'specialty' => 'Cardiology',
        'exam_date' => now()->addMonth(),
        'start_time' => '09:00',
        'end_time' => '12:00',
        'location' => 'Test Location',
        'capacity' => 50,
        'minimum_grade' => 10,
        'status' => 'scheduled',
        'duration' => 180,
    ]);

    $service = app(ExamEligibilityService::class);
    $check = $service->checkEligibility($exam, $this->memberUser->id);

    expect($check)->toHaveKey('eligible');
});

test('admin can access exams index page', function () {
    $response = $this->actingAs($this->adminUser)
        ->get(route('admin.exams.index'));

    $response->assertSuccessful();
});

test('admin can create exam', function () {
    $examData = [
        'name' => 'New Exam',
        'type' => 'teorico',
        'specialty' => 'Cardiology',
        'exam_date' => now()->addMonth()->format('Y-m-d'),
        'start_time' => '09:00',
        'end_time' => '12:00',
        'location' => 'Test Location',
        'capacity' => 50,
        'minimum_grade' => 10,
        'allow_consultation' => false,
        'is_mandatory' => true,
        'immediate_result' => false,
        'attempts_allowed' => 1,
    ];

    $response = $this->actingAs($this->adminUser)
        ->post(route('admin.exams.store'), $examData);

    $response->assertRedirect();
    $this->assertDatabaseHas('exams', ['name' => 'New Exam']);
});

test('member can view available exams', function () {
    Exam::create([
        'name' => 'Available Exam',
        'type' => 'teorico',
        'specialty' => 'Cardiology',
        'exam_date' => now()->addMonth(),
        'start_time' => '09:00',
        'end_time' => '12:00',
        'location' => 'Test Location',
        'capacity' => 50,
        'minimum_grade' => 10,
        'status' => 'scheduled',
        'duration' => 180,
    ]);

    $response = $this->actingAs($this->memberUser)
        ->get(route('member.exams.available'));

    $response->assertSuccessful();
});
