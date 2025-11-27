<?php

use App\Models\SystemConfig;
use App\Models\User;
use App\Services\SystemConfigService;
use Database\Seeders\AdminPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class);
uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(AdminPermissionsSeeder::class);
    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin');
    $this->actingAs($this->admin);
});

it('can access system dashboard', function () {
    $response = $this->get(route('admin.system.dashboard'));

    $response->assertSuccessful();
    $response->assertViewIs('admin.system.dashboard');
});

it('can access system configurations index', function () {
    $response = $this->get(route('admin.system.configs.index'));

    $response->assertSuccessful();
    $response->assertViewIs('admin.system.index');
});

it('can access system configurations create page', function () {
    $response = $this->get(route('admin.system.configs.create'));

    $response->assertSuccessful();
    $response->assertViewIs('admin.system.create');
});

it('can create a new system configuration', function () {
    $data = [
        'key' => 'test.config',
        'value' => 'test value',
        'description' => 'Test configuration',
        'group' => 'test',
        'is_public' => true,
    ];

    $response = $this->post(route('admin.system.configs.store'), $data);

    $response->assertRedirect(route('admin.system.configs.index', ['group' => 'test']));
    $response->assertSessionHas('success', 'Configuration created successfully.');

    $this->assertDatabaseHas('system_configs', [
        'key' => 'test.config',
        'description' => 'Test configuration',
    ]);
});

it('validates required fields when creating configuration', function () {
    $response = $this->post(route('admin.system.configs.store'), []);

    $response->assertSessionHasErrors(['key', 'value', 'group']);
});

it('validates unique key when creating configuration', function () {
    SystemConfig::create([
        'key' => 'existing.config',
        'value' => 'existing value',
        'description' => 'Existing configuration',
        'group' => 'test',
        'is_public' => false,
    ]);

    $data = [
        'key' => 'existing.config',
        'value' => 'new value',
        'description' => 'New configuration',
        'group' => 'test',
    ];

    $response = $this->post(route('admin.system.configs.store'), $data);

    $response->assertSessionHasErrors(['key']);
});

it('can access system configuration edit page', function () {
    $config = SystemConfig::create([
        'key' => 'test.config',
        'value' => 'test value',
        'description' => 'Test configuration',
        'group' => 'test',
        'is_public' => false,
    ]);

    $response = $this->get(route('admin.system.configs.edit', $config->key));

    $response->assertSuccessful();
    $response->assertViewIs('admin.system.edit');
    $response->assertViewHas('config', $config);
});

it('can update system configuration', function () {
    $config = SystemConfig::create([
        'key' => 'test.config',
        'value' => 'old value',
        'description' => 'Old description',
        'group' => 'test',
        'is_public' => false,
    ]);

    $data = [
        'value' => 'new value',
        'description' => 'New description',
        'group' => 'updated',
        'is_public' => true,
    ];

    $response = $this->put(route('admin.system.configs.update', $config->key), $data);

    $response->assertRedirect(route('admin.system.configs.index', ['group' => 'updated']));
    $response->assertSessionHas('success', 'Configuration updated successfully.');

    $this->assertDatabaseHas('system_configs', [
        'key' => 'test.config',
        'description' => 'New description',
    ]);
});

it('can delete system configuration', function () {
    $config = SystemConfig::create([
        'key' => 'test.config',
        'value' => 'test value',
        'description' => 'Test configuration',
        'group' => 'test',
        'is_public' => false,
    ]);

    $response = $this->delete(route('admin.system.configs.destroy', $config->key));

    $response->assertRedirect(route('admin.system.configs.index', ['group' => 'test']));
    $response->assertSessionHas('success', 'Configuration deleted successfully.');

    $this->assertDatabaseMissing('system_configs', [
        'key' => 'test.config',
    ]);
});

it('can access backup management page', function () {
    $response = $this->get(route('admin.system.backups'));

    $response->assertSuccessful();
    $response->assertViewIs('admin.system.backups');
});

it('can update backup settings', function () {
    $data = [
        'automatic_backups' => true,
        'backup_frequency' => 'daily',
        'backup_retention' => 30,
        'backup_destination' => 'local',
    ];

    $response = $this->post(route('admin.system.update-backup-settings'), $data);

    $response->assertRedirect(route('admin.system.backups'));
    $response->assertSessionHas('success', 'Backup settings updated successfully.');

    $this->assertEquals(true, SystemConfigService::get('backup.automatic'));
    $this->assertEquals('daily', SystemConfigService::get('backup.frequency'));
    $this->assertEquals(30, SystemConfigService::get('backup.retention'));
    $this->assertEquals('local', SystemConfigService::get('backup.destination'));
});

it('can create manual backup', function () {
    $response = $this->post(route('admin.system.create-backup'));

    $response->assertRedirect(route('admin.system.backups'));
    $response->assertSessionHas('success', 'Backup created successfully.');
});

it('can restore from backup', function () {
    $data = [
        'backup_file' => 'backup_2024-01-01_12-00-00.zip',
    ];

    $response = $this->post(route('admin.system.restore-backup'), $data);

    $response->assertRedirect(route('admin.system.backups'));
    $response->assertSessionHas('success', 'Restore completed successfully.');
});

it('validates backup settings', function () {
    $data = [
        'backup_frequency' => 'invalid',
        'backup_retention' => 0,
        'backup_destination' => 'invalid',
    ];

    $response = $this->post(route('admin.system.update-backup-settings'), $data);

    $response->assertSessionHasErrors(['backup_frequency', 'backup_retention', 'backup_destination']);
});

it('validates restore backup file', function () {
    $response = $this->post(route('admin.system.restore-backup'), []);

    $response->assertSessionHasErrors(['backup_file']);
});

it('enforces admin middleware on system routes', function () {
    // Create a regular user without admin role
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('admin.system.dashboard'));

    $response->assertRedirect(); // Middleware redirects instead of returning 403
});

it('allows admin users to access system routes', function () {
    $response = $this->get(route('admin.system.dashboard'));

    $response->assertSuccessful();
});
