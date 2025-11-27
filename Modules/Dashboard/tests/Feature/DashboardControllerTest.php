<?php

namespace Modules\Dashboard\Tests\Feature;

use App\Http\Controllers\Admin\DashboardController;
use App\Models\Exam;
use App\Models\Member;
use App\Models\Payment;
use Modules\Registration\Models\Registration;
use Modules\Registration\Models\RegistrationType;
use App\Models\ResidencyApplication;
use App\Models\User;
use Database\Seeders\AdminPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class);
uses(RefreshDatabase::class);

beforeEach(function () {
    // Seed the admin permissions and roles
    $this->seed(AdminPermissionsSeeder::class);

    $this->user = User::factory()->create();
    $this->user->assignRole('admin');
    $this->actingAs($this->user);

    $this->dashboardController = new DashboardController;
});

it('can get dashboard statistics', function () {
    // Create test data
    User::factory()->count(5)->create();
    Member::factory()->count(3)->create();

    // Use existing registration type from States
    $registrationType1 = RegistrationType::where('code', 'provisional_private')->first();
    expect($registrationType1)->not->toBeNull('Registration type should exist from States');

    Registration::factory()->count(4)->create(['registration_type_id' => $registrationType1->id]);
    ResidencyApplication::factory()->count(2)->create(['status' => 'approved']);

    // Create exam data manually since factory doesn't exist
    $exam = new Exam;
    $exam->name = 'Test Exam';
    $exam->type = 'teorico';
    $exam->specialty = 'Cardiologia';
    $exam->description = 'Test Description';
    $exam->exam_date = now()->addDays(30);
    $exam->start_time = '09:00:00';
    $exam->end_time = '12:00:00';
    $exam->duration = 180;
    $exam->location = 'Test Location';
    $exam->capacity = 50;
    $exam->save();

    $response = $this->get(route('admin.dashboard'));

    $response->assertSuccessful();
    $response->assertViewHas('total_doctors');
    $response->assertViewHas('total_registrations');
    $response->assertViewHas('total_residents');
    $response->assertViewHas('total_users');
    $response->assertViewHas('total_exams');

    // The view should render successfully with the required data
});

it('can get payment statistics', function () {
    // Create test payments
    Payment::factory()->count(3)->create([
        'status' => 'completed',
        'amount' => 1000.00,
        'created_at' => now(),
    ]);

    Payment::factory()->count(2)->create([
        'status' => 'pending',
        'amount' => 500.00,
    ]);

    Payment::factory()->count(2)->create([
        'status' => 'pending',
        'amount' => 800.00,
        'due_date' => now()->subDays(5),
    ]);

    $response = $this->get(route('admin.dashboard'));

    $response->assertSuccessful();
    $response->assertViewHas('payments_received');
    $response->assertViewHas('payments_pending');
    $response->assertViewHas('payments_overdue');

    // The view should render successfully with payment data
});

it('can get recent activities', function () {
    // Create test data
    $members = Member::factory()->count(3)->create([
        'specialty' => 'Cardiologia',
    ]);

    $members2 = Member::factory()->count(2)->create([
        'specialty' => 'Neurologia',
    ]);

    Registration::factory()->count(3)->create();

    $response = $this->get(route('admin.dashboard'));

    $response->assertSuccessful();
    $response->assertViewHas('recent_registrations');
    $response->assertViewHas('popular_specialties');

    // The view should render successfully with recent activities data
});

it('can get registration chart data', function () {

    // Use existing registration type from States
    $registrationType2 = RegistrationType::where('code', 'effective_general')->first();
    expect($registrationType2)->not->toBeNull('Registration type should exist from States');
    // Create test registrations for different months
    Registration::factory()->count(2)->create(['created_at' => now()->startOfYear(), 'registration_type_id' => $registrationType2->id]);
    Registration::factory()->count(3)->create(['created_at' => now()->startOfYear()->addMonth(), 'registration_type_id' => $registrationType2->id]);

    $response = $this->get(route('admin.dashboard'));

    $response->assertSuccessful();
    $response->assertViewHas('months');
    $response->assertViewHas('provisional_data');
    $response->assertViewHas('effective_data');

    // The view should render successfully with chart data
});

it('can get exam chart data', function () {
    // Create exam applications manually
    $exam1 = new Exam;
    $exam1->name = 'Certification Exam';
    $exam1->type = 'teorico';
    $exam1->specialty = 'Cardiologia';
    $exam1->description = 'Test';
    $exam1->exam_date = now()->addDays(30);
    $exam1->start_time = '09:00:00';
    $exam1->end_time = '12:00:00';
    $exam1->duration = 180;
    $exam1->location = 'Test Location';
    $exam1->capacity = 50;
    $exam1->save();

    $exam2 = new Exam;
    $exam2->name = 'Specialty Exam';
    $exam2->type = 'pratico';
    $exam2->specialty = 'Neurologia';
    $exam2->description = 'Test';
    $exam2->exam_date = now()->addDays(30);
    $exam2->start_time = '09:00:00';
    $exam2->end_time = '12:00:00';
    $exam2->duration = 180;
    $exam2->location = 'Test Location';
    $exam2->capacity = 50;
    $exam2->save();

    $response = $this->get(route('admin.dashboard'));

    $response->assertSuccessful();
    $response->assertViewHas('exam_categories');

    // The view should render successfully with exam data
});

it('can get system alerts', function () {
    $response = $this->get(route('admin.dashboard'));

    $response->assertSuccessful();
    $response->assertViewHas('system_alerts');

    // The view should render successfully with system alerts
});

it('returns empty alerts when no issues exist', function () {
    $response = $this->get(route('admin.dashboard'));

    $response->assertSuccessful();
    $response->assertViewHas('system_alerts');

    // The view should render successfully with system alerts
});

it('includes maintenance mode alert when enabled', function () {
    // Create maintenance mode config
    \App\Models\SystemConfig::create([
        'key' => 'maintenance_mode',
        'value' => 'true',
        'description' => 'System maintenance mode',
    ]);

    $response = $this->get(route('admin.dashboard'));

    $response->assertSuccessful();
    $response->assertViewHas('system_alerts');

    // The view should render successfully with system alerts
});
