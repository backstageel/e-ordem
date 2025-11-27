<?php

use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

Artisan::command('ensure-database-state-is-loaded', function () {
    $this->info('Ensuring Database State is Loaded1');
    collect([
        new Database\States\EnsureAdministratorIsPresent,
        new Database\States\EnsureInitialConfigIsDone,
        new Database\States\EnsureRegistrationTypesArePresent,
        new Database\States\EnsureDocumentTypesArePresent,
    ])->each->__invoke();
})->purpose('Load Default Data to Database');

Artisan::command('ensure-super-admin-user', function () {
    $role = Role::firstOrCreate(['name' => 'super-admin']);
    $permissions = Permission::all();
    $role->syncPermissions($permissions);
});
