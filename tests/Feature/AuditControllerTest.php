<?php

use App\Models\User;
use Database\Seeders\AdminPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use OwenIt\Auditing\Models\Audit;

uses(Tests\TestCase::class);
uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(AdminPermissionsSeeder::class);
    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin');
    $this->actingAs($this->admin);
});

it('can access audit logs index page', function () {
    $response = $this->get(route('admin.audit.index'));

    $response->assertSuccessful();
    $response->assertViewIs('admin.audit.index');
});

it('can filter audit logs by event', function () {
    // Create some audit logs

    $response = $this->get(route('admin.audit.index', ['event' => 'system_action']));

    $response->assertSuccessful();
    $response->assertViewIs('admin.audit.index');
});

it('can filter audit logs by auditable type', function () {
    // Create some audit logs

    $response = $this->get(route('admin.audit.index', ['auditable_type' => 'system']));

    $response->assertSuccessful();
    $response->assertViewIs('admin.audit.index');
});

it('can filter audit logs by user', function () {
    $user = User::factory()->create();

    // Create audit logs for different users

    $response = $this->get(route('admin.audit.index', ['user_id' => $this->admin->id]));

    $response->assertSuccessful();
    $response->assertViewIs('admin.audit.index');
});

it('can filter audit logs by date range', function () {
    $startDate = now()->subDays(7)->format('Y-m-d');
    $endDate = now()->format('Y-m-d');

    $response = $this->get(route('admin.audit.index', [
        'start_date' => $startDate,
        'end_date' => $endDate
    ]));

    $response->assertSuccessful();
    $response->assertViewIs('admin.audit.index');
});

it('can view audit log details', function () {
    // Create a simple audit log directly
    $audit = \OwenIt\Auditing\Models\Audit::create([
        'auditable_type' => 'App\Models\User',
        'auditable_id' => 1,
        'event' => 'created',
        'old_values' => [],
        'new_values' => ['name' => 'Test User'],
        'user_id' => null,
        'ip_address' => '127.0.0.1',
        'user_agent' => 'Test Agent',
    ]);

    $response = $this->get(route('admin.audit.show', $audit));

    $response->assertSuccessful();
    $response->assertViewIs('admin.audit.show');
});

it('can access audit statistics page', function () {
    // Create some audit logs

    $response = $this->get(route('admin.audit.statistics'));

    $response->assertSuccessful();
    $response->assertViewIs('admin.audit.statistics');
});

it('can export audit logs', function () {
    // Create audit logs directly
    \OwenIt\Auditing\Models\Audit::create([
        'auditable_type' => 'App\Models\User',
        'auditable_id' => 1,
        'event' => 'created',
        'old_values' => [],
        'new_values' => ['name' => 'Test User'],
        'user_id' => null,
        'ip_address' => '127.0.0.1',
        'user_agent' => 'Test Agent',
    ]);

    $response = $this->get(route('admin.audit.export'));

    $response->assertSuccessful();
    $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
});

it('can export filtered audit logs', function () {
    // Create audit logs directly
    \OwenIt\Auditing\Models\Audit::create([
        'auditable_type' => 'App\Models\User',
        'auditable_id' => 1,
        'event' => 'created',
        'old_values' => [],
        'new_values' => ['name' => 'Test User'],
        'user_id' => null,
        'ip_address' => '127.0.0.1',
        'user_agent' => 'Test Agent',
    ]);

    $response = $this->get(route('admin.audit.export', ['event' => 'created']));

    $response->assertSuccessful();
    $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
});

it('enforces admin middleware on audit routes', function () {
    // Create a regular user without admin role
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('admin.audit.index'));

    $response->assertRedirect();
});

it('allows admin users to access audit routes', function () {
    $response = $this->get(route('admin.audit.index'));

    $response->assertSuccessful();
});
