<?php

use App\Models\User;
use Database\Seeders\AdminPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(Tests\TestCase::class);
uses(RefreshDatabase::class);

beforeEach(function () {
    // Seed the admin permissions and roles
    $this->seed(AdminPermissionsSeeder::class);
});

it('can create and assign permissions to users', function () {
    $user = User::factory()->create();
    $permission = Permission::create(['name' => 'test.permission']);

    $user->givePermissionTo('test.permission');

    expect($user->hasPermissionTo('test.permission'))->toBeTrue();
});

it('can create and assign roles to users', function () {
    $user = User::factory()->create();
    $role = Role::create(['name' => 'test-role']);

    $user->assignRole('test-role');

    expect($user->hasRole('test-role'))->toBeTrue();
});

it('can check if user has permission through role', function () {
    $user = User::factory()->create();
    $role = Role::create(['name' => 'test-role']);
    $permission = Permission::create(['name' => 'test.permission']);

    $role->givePermissionTo('test.permission');
    $user->assignRole('test-role');

    expect($user->hasPermissionTo('test.permission'))->toBeTrue();
});

it('can sync permissions to role', function () {
    $role = Role::create(['name' => 'test-role']);
    $permissions = [
        Permission::create(['name' => 'permission.1']),
        Permission::create(['name' => 'permission.2']),
    ];

    $role->syncPermissions($permissions);

    expect($role->permissions->count())->toBe(2);
    expect($role->hasPermissionTo('permission.1'))->toBeTrue();
    expect($role->hasPermissionTo('permission.2'))->toBeTrue();
});

it('can check if user has any of multiple roles', function () {
    $user = User::factory()->create();
    $role1 = Role::create(['name' => 'role1']);
    $role2 = Role::create(['name' => 'role2']);

    $user->assignRole('role1');

    expect($user->hasAnyRole(['role1', 'role2']))->toBeTrue();
    expect($user->hasAnyRole(['role2', 'role3']))->toBeFalse();
});

it('can check if user has all of multiple roles', function () {
    $user = User::factory()->create();
    $role1 = Role::create(['name' => 'role1']);
    $role2 = Role::create(['name' => 'role2']);

    $user->assignRole(['role1', 'role2']);

    expect($user->hasAllRoles(['role1', 'role2']))->toBeTrue();
    expect($user->hasAllRoles(['role1', 'role3']))->toBeFalse();
});

it('can revoke permissions from user', function () {
    $user = User::factory()->create();
    $permission = Permission::create(['name' => 'test.permission']);

    $user->givePermissionTo('test.permission');
    expect($user->hasPermissionTo('test.permission'))->toBeTrue();

    $user->revokePermissionTo('test.permission');
    expect($user->hasPermissionTo('test.permission'))->toBeFalse();
});

it('can revoke roles from user', function () {
    $user = User::factory()->create();
    $role = Role::create(['name' => 'test-role']);

    $user->assignRole('test-role');
    expect($user->hasRole('test-role'))->toBeTrue();

    $user->removeRole('test-role');
    expect($user->hasRole('test-role'))->toBeFalse();
});

it('can sync roles to user', function () {
    $user = User::factory()->create();
    $role1 = Role::create(['name' => 'role1']);
    $role2 = Role::create(['name' => 'role2']);
    $role3 = Role::create(['name' => 'role3']);

    $user->assignRole(['role1', 'role2']);
    expect($user->roles->count())->toBe(2);

    $user->syncRoles(['role2', 'role3']);
    expect($user->roles->count())->toBe(2);
    expect($user->hasRole('role1'))->toBeFalse();
    expect($user->hasRole('role2'))->toBeTrue();
    expect($user->hasRole('role3'))->toBeTrue();
});
