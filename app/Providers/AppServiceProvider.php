<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // Set application locale to Portuguese
        app()->setLocale('pt');

        // Register model observers
        \App\Models\Payment::observe(\App\Observers\PaymentObserver::class);
    }
}
