<?php

namespace Modules\Dashboard\Tests\Feature;

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
});

it('can access admin dashboard', function () {
    $response = $this->get(route('admin.dashboard'));

    $response->assertSuccessful();
    $response->assertViewIs('admin.dashboard');
});

it('passes required variables to dashboard view', function () {
    $response = $this->get(route('admin.dashboard'));

    $response->assertSuccessful();

    // Check that all required variables are passed to the view
    $response->assertViewHas([
        'total_doctors',
        'total_registrations',
        'total_exams',
        'total_residents',
        'total_users',
        'doctors_growth',
        'registrations_growth',
        'exams_growth',
        'residents_growth',
        'payments_received',
        'payments_pending',
        'payments_overdue',
        'payments_growth',
        'recent_registrations',
        'popular_specialties',
        'months',
        'provisional_data',
        'effective_data',
        'exam_categories',
        'system_alerts',
    ]);
});

it('dashboard view renders without errors', function () {
    $response = $this->get(route('admin.dashboard'));

    $response->assertSuccessful();
    $response->assertSee('Dashboard Administrativo');
    $response->assertSee('Bem-vindo');
});
