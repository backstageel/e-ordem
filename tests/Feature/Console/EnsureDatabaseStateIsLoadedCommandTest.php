<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class);
uses(RefreshDatabase::class);

it('executes ensure-database-state-is-loaded command successfully', function () {
    $this->artisan('ensure-database-state-is-loaded')
        ->expectsOutput('Ensuring Database State is Loaded1')
        ->assertSuccessful();
});

it('loads default database states', function () {
    $this->artisan('ensure-database-state-is-loaded')
        ->assertSuccessful();

    // Verify that the states have been loaded by checking some expected data
    // This will depend on what the states actually do
    // For example, if EnsureRegistrationTypesArePresent creates registration types:
    // $this->assertDatabaseHas('registration_types', [...]);
});

