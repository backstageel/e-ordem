<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(Tests\TestCase::class);
uses(RefreshDatabase::class);

it('creates super-admin role if it does not exist', function () {
    // Ensure role doesn't exist initially
    Role::where('name', 'super-admin')->delete();
    $this->assertDatabaseMissing('roles', ['name' => 'super-admin']);

    $this->artisan('ensure-super-admin-user')
        ->assertSuccessful();

    $this->assertDatabaseHas('roles', ['name' => 'super-admin']);
});

it('assigns all permissions to super-admin role', function () {
    // Create some test permissions
    $permission1 = Permission::create(['name' => 'test-permission-1']);
    $permission2 = Permission::create(['name' => 'test-permission-2']);

    $this->artisan('ensure-super-admin-user')
        ->assertSuccessful();

    $role = Role::where('name', 'super-admin')->first();
    
    expect($role)->not->toBeNull()
        ->and($role->hasPermissionTo($permission1))->toBeTrue()
        ->and($role->hasPermissionTo($permission2))->toBeTrue();
});

it('syncs permissions even if super-admin role already exists', function () {
    // Ensure role exists first
    $role = Role::firstOrCreate(['name' => 'super-admin']);
    $role->permissions()->detach(); // Clear existing permissions
    
    $permission = Permission::create(['name' => 'new-permission']);

    // Run command again
    $this->artisan('ensure-super-admin-user')
        ->assertSuccessful();

    // Verify the new permission was added
    $role->refresh();
    expect($role->hasPermissionTo($permission))->toBeTrue();
});

