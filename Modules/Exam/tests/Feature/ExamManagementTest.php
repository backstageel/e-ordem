<?php

namespace Modules\Exam\Tests\Feature;

use App\Models\Exam;
use App\Models\ExamApplication;
use App\Models\User;
use Spatie\Permission\Models\Role;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);
uses(\Illuminate\Foundation\Testing\WithFaker::class);

beforeEach(function () {
    // Create roles
    Role::firstOrCreate(['name' => 'admin']);
    Role::firstOrCreate(['name' => 'member']);
    Role::firstOrCreate(['name' => 'teacher']);
    Role::firstOrCreate(['name' => 'evaluator']);

    // Create a user for authentication
    $this->user = User::factory()->create([
        'two_factor_enabled' => false,
        'email_verified_at' => now(),
    ]);

    // Assign admin role to the user
    $this->user->assignRole('admin');

    // Create an evaluator user
    $this->evaluator = User::factory()->create();
    $this->evaluator->assignRole('evaluator');
});

test('exams index page can be rendered', function () {
    $response = $this->actingAs($this->user)
        ->get(route('admin.exams.index'));

    $response->assertStatus(200);
    $response->assertViewIs('admin.exams.index');
});

test('exam create page can be rendered', function () {
    $response = $this->actingAs($this->user)
        ->get(route('admin.exams.create'));

    $response->assertStatus(200);
    $response->assertViewIs('admin.exams.create');
});

test('exam can be created', function () {
    $examData = [
        'name' => 'Test Exam',
        'type' => 'teorico',
        'level' => 'intermediario',
        'specialty' => 'Cardiology',
        'description' => 'A test exam for cardiology specialists',
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
    ];

    $response = $this->actingAs($this->user)
        ->post(route('admin.exams.store'), $examData);

    $response->assertRedirect();
    $this->assertDatabaseHas('exams', [
        'name' => 'Test Exam',
        'type' => 'teorico',
        'specialty' => 'Cardiology',
        'status' => 'draft',
    ]);
});

test('exam show page can be rendered', function () {
    $exam = Exam::create([
        'name' => 'Test Exam',
        'type' => 'teorico',
        'specialty' => 'Cardiology',
        'exam_date' => now()->addMonth()->format('Y-m-d'),
        'start_time' => '09:00',
        'end_time' => '12:00',
        'location' => 'Test Location',
        'capacity' => 50,
        'minimum_grade' => 10,
        'status' => 'draft',
        'duration' => 180,
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('admin.exams.show', $exam));

    $response->assertStatus(200);
    $response->assertViewIs('admin.exams.show');
    $response->assertViewHas('exam');
});

test('exam edit page can be rendered', function () {
    $exam = Exam::create([
        'name' => 'Test Exam',
        'type' => 'teorico',
        'specialty' => 'Cardiology',
        'exam_date' => now()->addMonth()->format('Y-m-d'),
        'start_time' => '09:00',
        'end_time' => '12:00',
        'location' => 'Test Location',
        'capacity' => 50,
        'minimum_grade' => 10,
        'status' => 'draft',
        'duration' => 180,
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('admin.exams.edit', $exam));

    $response->assertStatus(200);
    $response->assertViewIs('admin.exams.edit');
    $response->assertViewHas('exam');
});

test('exam can be updated', function () {
    $exam = Exam::create([
        'name' => 'Test Exam',
        'type' => 'teorico',
        'specialty' => 'Cardiology',
        'exam_date' => now()->addMonth()->format('Y-m-d'),
        'start_time' => '09:00',
        'end_time' => '12:00',
        'location' => 'Test Location',
        'capacity' => 50,
        'minimum_grade' => 10,
        'status' => 'draft',
        'duration' => 180,
    ]);

    $updatedData = [
        'name' => 'Updated Exam',
        'type' => 'pratico',
        'level' => 'avancado',
        'specialty' => 'Neurology',
        'description' => 'An updated test exam',
        'exam_date' => now()->addMonths(2)->format('Y-m-d'),
        'start_time' => '10:00',
        'end_time' => '14:00',
        'location' => 'Updated Location',
        'address' => 'Updated Address',
        'capacity' => 75,
        'minimum_grade' => 12,
        'questions_count' => 120,
        'time_limit' => 240,
        'attempts_allowed' => 2,
        'allow_consultation' => true,
        'is_mandatory' => false,
        'immediate_result' => true,
        'primary_evaluator_id' => $this->evaluator->id,
        'notes' => 'Updated notes',
        'status' => 'draft',
    ];

    $response = $this->actingAs($this->user)
        ->put(route('admin.exams.update', $exam), $updatedData);

    $response->assertRedirect();
    $this->assertDatabaseHas('exams', [
        'id' => $exam->id,
        'name' => 'Updated Exam',
        'type' => 'pratico',
        'specialty' => 'Neurology',
    ]);
});

test('exam can be deleted', function () {
    $exam = Exam::create([
        'name' => 'Test Exam',
        'type' => 'teorico',
        'specialty' => 'Cardiology',
        'exam_date' => now()->addMonth()->format('Y-m-d'),
        'start_time' => '09:00',
        'end_time' => '12:00',
        'location' => 'Test Location',
        'capacity' => 50,
        'minimum_grade' => 10,
        'status' => 'draft',
        'duration' => 180,
    ]);

    $response = $this->actingAs($this->user)
        ->delete(route('admin.exams.destroy', $exam));

    $response->assertRedirect();
    $this->assertSoftDeleted('exams', ['id' => $exam->id]);
});

test('exam cannot be deleted if has applications', function () {
    $exam = Exam::create([
        'name' => 'Test Exam',
        'type' => 'teorico',
        'specialty' => 'Cardiology',
        'exam_date' => now()->addMonth()->format('Y-m-d'),
        'start_time' => '09:00',
        'end_time' => '12:00',
        'location' => 'Test Location',
        'capacity' => 50,
        'minimum_grade' => 10,
        'status' => 'draft',
        'duration' => 180,
    ]);

    // Create an application for this exam
    ExamApplication::create([
        'exam_id' => $exam->id,
        'user_id' => $this->user->id,
        'exam_type' => 'certificacao',
        'specialty' => 'Cardiology',
        'status' => 'submitted',
    ]);

    $response = $this->actingAs($this->user)
        ->delete(route('admin.exams.destroy', $exam));

    $response->assertRedirect();
    $response->assertSessionHas('error');
    $this->assertDatabaseHas('exams', ['id' => $exam->id]);
});

test('exam schedule page can be rendered', function () {
    $exam = Exam::create([
        'name' => 'Test Exam',
        'type' => 'teorico',
        'specialty' => 'Cardiology',
        'exam_date' => now()->addMonth()->format('Y-m-d'),
        'start_time' => '09:00',
        'end_time' => '12:00',
        'location' => 'Test Location',
        'capacity' => 50,
        'minimum_grade' => 10,
        'status' => 'draft',
        'duration' => 180,
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('admin.exams.schedule', $exam));

    $response->assertStatus(200);
    $response->assertViewIs('admin.exams.schedule');
    $response->assertViewHas('exam');
});

test('exam can be scheduled', function () {
    $exam = Exam::create([
        'name' => 'Test Exam',
        'type' => 'teorico',
        'specialty' => 'Cardiology',
        'exam_date' => now()->addMonth()->format('Y-m-d'),
        'start_time' => '09:00',
        'end_time' => '12:00',
        'location' => 'Test Location',
        'capacity' => 50,
        'minimum_grade' => 10,
        'status' => 'draft',
        'duration' => 180,
    ]);

    $scheduleData = [
        'exam_date' => now()->addMonths(2)->format('Y-m-d'),
        'start_time' => '10:00',
        'end_time' => '14:00',
        'location' => 'Scheduled Location',
        'address' => 'Scheduled Address',
    ];

    $response = $this->actingAs($this->user)
        ->post(route('admin.exams.schedule', $exam), $scheduleData);

    $response->assertRedirect();
    $this->assertDatabaseHas('exams', [
        'id' => $exam->id,
        'exam_date' => $scheduleData['exam_date'],
        'location' => 'Scheduled Location',
        'status' => 'scheduled',
    ]);
});

test('exam candidates page can be rendered', function () {
    $exam = Exam::create([
        'name' => 'Test Exam',
        'type' => 'teorico',
        'specialty' => 'Cardiology',
        'exam_date' => now()->addMonth()->format('Y-m-d'),
        'start_time' => '09:00',
        'end_time' => '12:00',
        'location' => 'Test Location',
        'capacity' => 50,
        'minimum_grade' => 10,
        'status' => 'scheduled',
        'duration' => 180,
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('admin.exams.candidates', $exam));

    $response->assertStatus(200);
    $response->assertViewIs('admin.exams.candidates');
    $response->assertViewHas('exam');
});

test('exam upload results page can be rendered', function () {
    $exam = Exam::create([
        'name' => 'Test Exam',
        'type' => 'teorico',
        'specialty' => 'Cardiology',
        'exam_date' => now()->addMonth()->format('Y-m-d'),
        'start_time' => '09:00',
        'end_time' => '12:00',
        'location' => 'Test Location',
        'capacity' => 50,
        'minimum_grade' => 10,
        'status' => 'scheduled',
        'duration' => 180,
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('admin.exams.upload-results', $exam));

    $response->assertStatus(200);
    $response->assertViewIs('admin.exams.upload-results');
    $response->assertViewHas('exam');
});

test('exam results can be processed', function () {
    $exam = Exam::create([
        'name' => 'Test Exam',
        'type' => 'teorico',
        'specialty' => 'Cardiology',
        'exam_date' => now()->addMonth()->format('Y-m-d'),
        'start_time' => '09:00',
        'end_time' => '12:00',
        'location' => 'Test Location',
        'capacity' => 50,
        'minimum_grade' => 10,
        'status' => 'scheduled',
        'duration' => 180,
    ]);

    // Create an application for this exam
    $application = ExamApplication::create([
        'exam_id' => $exam->id,
        'user_id' => $this->user->id,
        'exam_type' => 'certificacao',
        'specialty' => 'Cardiology',
        'status' => 'approved',
    ]);

    $resultsData = [
        'results' => [
            [
                'application_id' => $application->id,
                'grade' => 15,
                'status' => 'presente',
            ],
        ],
        'decision_type' => 'aprovacao_automatica',
        'notes' => 'Test results notes',
        'notify_candidates' => false,
    ];

    $response = $this->actingAs($this->user)
        ->post(route('admin.exams.process-results', $exam), $resultsData);

    $response->assertRedirect();
    $this->assertDatabaseHas('exam_results', [
        'exam_application_id' => $application->id,
        'grade' => '15.0',
        'status' => 'presente',
        'decision' => 'aprovado',
        'decision_type' => 'aprovacao_automatica',
    ]);
    $this->assertDatabaseHas('exams', [
        'id' => $exam->id,
        'status' => 'completed',
    ]);
});

test('exam generate lists page can be rendered', function () {
    $exam = Exam::create([
        'name' => 'Test Exam',
        'type' => 'teorico',
        'specialty' => 'Cardiology',
        'exam_date' => now()->addMonth()->format('Y-m-d'),
        'start_time' => '09:00',
        'end_time' => '12:00',
        'location' => 'Test Location',
        'capacity' => 50,
        'minimum_grade' => 10,
        'status' => 'completed',
        'duration' => 180,
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('admin.exams.generate-lists', $exam));

    $response->assertStatus(200);
    $response->assertViewIs('admin.exams.generate-lists');
    $response->assertViewHas('exam');
});

test('exam history page can be rendered', function () {
    $response = $this->actingAs($this->user)
        ->get(route('admin.exams.history'));

    $response->assertStatus(200);
    $response->assertViewIs('admin.exams.history');
});
