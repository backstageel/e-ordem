<?php

namespace App\Listeners;

use Illuminate\Database\Events\NoPendingMigrations;
use Illuminate\Support\Facades\Artisan;

class EnsureDatabaseStatusAreLoadedEvenIfNoMigrations
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
    public function handle(NoPendingMigrations $event): void
    {
        Artisan::call('ensure-database-state-is-loaded');
        $this->runIfCommandExists('initializeDatabaseStates');
    }

    protected function runIfCommandExists(string $command): void
    {
        $commands = Artisan::all(); // Get all registered commands as an array
        if (array_key_exists($command, $commands)) {
            Artisan::call($command);
        }
    }
}
