<?php

use App\Actions\Admin\CreateUserAction;
use App\Actions\Admin\DeleteUserAction;
use App\Actions\Admin\UpdateUserAction;
use App\Data\RoleData;
use App\Data\UserData;
use App\Models\User;
use Database\Seeders\AdminPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
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

it('can access user management index page', function () {
    $response = $this->get(route('admin.users.index'));

    $response->assertSuccessful();
    $response->assertViewIs('admin.users.index');
});

it('can create a new user using CreateUserAction', function () {
    $userData = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'roles' => ['secretariat'],
    ];

    $action = new CreateUserAction;
    $user = $action->execute($userData);

    expect($user)->toBeInstanceOf(User::class);
    expect($user->name)->toBe('Test User');
    expect($user->email)->toBe('test@example.com');
    expect($user->hasRole('secretariat'))->toBeTrue();
});

it('can update a user using UpdateUserAction', function () {
    $user = User::factory()->create();
    $user->assignRole('secretariat');

    $updateData = [
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
        'roles' => ['admin'],
    ];

    $action = new UpdateUserAction;
    $updatedUser = $action->execute($user, $updateData);

    expect($updatedUser->name)->toBe('Updated Name');
    expect($updatedUser->email)->toBe('updated@example.com');
    expect($updatedUser->hasRole('admin'))->toBeTrue();
    expect($updatedUser->hasRole('secretariat'))->toBeFalse();
});

it('can delete a user using DeleteUserAction', function () {
    $user = User::factory()->create();
    $userId = $user->id;

    $action = new DeleteUserAction;
    $result = $action->execute($user);

    expect($result)->toBeTrue();
    expect(User::find($userId))->toBeNull();
});

it('prevents admin from deleting themselves', function () {
    $action = new DeleteUserAction;

    expect(fn () => $action->execute($this->admin))
        ->toThrow(Exception::class, 'You cannot delete your own account.');
});

it('can create UserData from User model', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');

    $userData = UserData::fromUser($user);

    expect($userData)->toBeInstanceOf(UserData::class);
    expect($userData->name)->toBe($user->name);
    expect($userData->email)->toBe($user->email);
    expect($userData->roles)->toContain('admin');
});

it('can create RoleData from Role model', function () {
    $role = Role::where('name', 'admin')->first();
    $roleData = RoleData::fromRole($role);

    expect($roleData)->toBeInstanceOf(RoleData::class);
    expect($roleData->name)->toBe('admin');
    expect($roleData->guard_name)->toBe('web');
});

it('validates user creation data correctly', function () {
    $userData = UserData::rules();

    expect($userData)->toHaveKey('name');
    expect($userData)->toHaveKey('email');
    expect($userData)->toHaveKey('password');
    expect($userData)->toHaveKey('roles');
});

it('validates role creation data correctly', function () {
    $roleData = RoleData::rules();

    expect($roleData)->toHaveKey('name');
    expect($roleData)->toHaveKey('guard_name');
    expect($roleData)->toHaveKey('permissions');
});

it('can access user create page', function () {
    $response = $this->get(route('admin.users.create'));

    $response->assertSuccessful();
    $response->assertViewIs('admin.users.create');
});

it('can access user edit page', function () {
    $user = User::factory()->create();

    $response = $this->get(route('admin.users.edit', $user));

    $response->assertSuccessful();
    $response->assertViewIs('admin.users.edit');
});

it('can access user show page', function () {
    $user = User::factory()->create();

    $response = $this->get(route('admin.users.show', $user));

    $response->assertSuccessful();
    $response->assertViewIs('admin.users.show');
});

// it('can access roles management page', function () {
//     $response = $this->get(route('admin.users.roles'));
//
//     $response->assertSuccessful();
// });

it('can access role create page', function () {
    $response = $this->get(route('admin.roles.create'));

    $response->assertSuccessful();
    $response->assertViewIs('admin.roles.create');
});

it('can access role edit page', function () {
    $role = Role::where('name', 'admin')->first();

    $response = $this->get(route('admin.roles.edit', $role));

    $response->assertSuccessful();
    $response->assertViewIs('admin.roles.edit');
});

it('can access change password page', function () {
    $user = User::factory()->create();

    $response = $this->get(route('admin.users.change-password', $user));

    $response->assertSuccessful();
    $response->assertViewIs('admin.users.change-password');
});

it('enforces admin middleware on user management routes', function () {
    $user = User::factory()->create();
    $user->assignRole('secretariat'); // Not admin
    $this->actingAs($user);

    $response = $this->get(route('admin.users.index'));

    $response->assertRedirect('/');
});

it('allows admin users to access user management routes', function () {
    $response = $this->get(route('admin.users.index'));

    $response->assertSuccessful();
});
