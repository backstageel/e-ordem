<?php

namespace App\Listeners;

use Illuminate\Database\Events\MigrationsEnded;
use Illuminate\Support\Facades\Artisan;

class EnsureDatabaseStatesAreLoaded
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MigrationsEnded $event): void
    {

        Artisan::call('ensure-database-state-is-loaded');
        $this->runIfCommandExists('initializeDatabaseStates');

        Artisan::call('ensure-super-admin-user');
    }

    protected function runIfCommandExists(string $command): string
    {
        $commands = Artisan::all(); // Get all registered commands as an array
        if (array_key_exists($command, $commands)) {
            Artisan::call($command);

            return Artisan::output();
        }

        return '';
    }
}
