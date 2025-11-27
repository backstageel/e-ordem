<x-layouts.app>
    <x-slot name="header">
        {{ __('Dashboard Secretariado') }}
    </x-slot>

    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="fw-bold mb-1">{{ __('Welcome') }}, {{ Auth::user()->name }}!</h3>
                    <p class="text-muted mb-0">{{ __('Here is the summary of registrations and documents.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards Placeholder -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="fw-bold mb-0">{{ __('Registration Statistics') }}</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-0">{{ __('Pending registrations, documents, and workflow items will be displayed here.') }}</p>
                    <!-- TODO: Add registration KPIs, pending documents, workflow statistics -->
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

