<?php

use App\Models\User;
use Database\Seeders\AdminPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Role as AppRole;

uses(Tests\TestCase::class);
uses(RefreshDatabase::class);

beforeEach(function () {
    // Seed the admin permissions and roles
    $this->seed(AdminPermissionsSeeder::class);

    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin');
    $this->actingAs($this->admin);

    // Ensure secretariat role exists with Portuguese display name
    AppRole::firstOrCreate(
        ['name' => 'secretariat'],
        [
            'guard_name' => 'web',
            'display_name' => 'Secretariado',
        ]
    );
});

it('can access user show page', function () {
    $user = User::factory()->create();
    $user->assignRole('secretariat');

    $response = $this->get(route('admin.users.show', $user));

    $response->assertSuccessful();
    $response->assertViewIs('admin.users.show');
});

it('displays user basic information', function () {
    $user = User::factory()->create([
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);
    $user->assignRole('secretariat');

    $response = $this->get(route('admin.users.show', $user));

    $response->assertSuccessful();
    $response->assertSee('Test User');
    $response->assertSee('test@example.com');
});

it('displays user roles correctly', function () {
    $user = User::factory()->create();
    $user->assignRole('secretariat');

    $response = $this->get(route('admin.users.show', $user));

    $response->assertSuccessful();
    $response->assertSee('Secretariado');
});
