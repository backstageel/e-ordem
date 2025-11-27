<x-layouts.app>
    <x-slot name="header">
        {{ __('My Dashboard') }}
    </x-slot>

    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="fw-bold mb-1">{{ __('Welcome') }}, {{ Auth::user()->name }}!</h3>
                    <p class="text-muted mb-0">{{ __('Here is your personal information and activities.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards Placeholder -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="fw-bold mb-0">{{ __('My Information') }}</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-0">{{ __('Profile, quotas, registrations, and cards will be displayed here.') }}</p>
                    <!-- TODO: Add member profile summary, quota status, active registrations, card info -->
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

