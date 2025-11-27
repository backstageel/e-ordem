<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - {{ __('Registration') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">

    <!-- Design System & Styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/accessibility.css') }}">

    <!-- Form Validation Script -->
    <script src="{{ asset('assets/js/form-validation.js') }}" defer></script>
    <script src="{{ asset('assets/js/keyboard-navigation.js') }}" defer></script>
</head>
<body class="font-sans text-dark guest-body" style="background-color: var(--neutral-50);">
    <!-- Skip to Content Link (Accessibility) -->
    <a href="#main-content" class="skip-to-content">{{ __('Skip to Content') }}</a>

    <!-- Top Navbar for Guest - Following Design System -->
    <nav class="navbar navbar-expand-lg guest-top-navbar" role="navigation" aria-label="{{ __('Main Navigation') }}">
        <div class="container-fluid px-3 px-md-4">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}" aria-label="{{ __('Home') }}">
                @if(file_exists(public_path('images/logo.png')))
                    <img src="{{ asset('images/logo.png') }}" alt="{{ __('Logo Ordem dos Médicos') }}" class="guest-logo me-2 me-md-3" height="40">
                @elseif(file_exists(public_path('images/ordem-logo.jpeg')))
                    <img src="{{ asset('images/ordem-logo.jpeg') }}" alt="{{ __('Logo Ordem dos Médicos') }}" class="guest-logo me-2 me-md-3" height="40">
                @else
                    <div class="guest-logo-placeholder me-2 me-md-3">
                        <i class="ti ti-hospital" aria-hidden="true"></i>
                    </div>
                @endif
                <span class="guest-brand-text d-none d-sm-inline">{{ __('Ordem dos Médicos') }}</span>
                <span class="guest-brand-text-short d-inline d-sm-none">OrMM</span>
            </a>
            <button class="navbar-toggler guest-navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#guestNavbar" aria-controls="guestNavbar" aria-expanded="false" aria-label="{{ __('Toggle Navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="guestNavbar">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link guest-nav-link" href="{{ route('login') }}">
                            <i class="ti ti-login me-1" aria-hidden="true"></i>
                            <span class="d-none d-md-inline">{{ __('Login') }}</span>
                            <span class="d-inline d-md-none">{{ __('Login') }}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main id="main-content" class="guest-main-content" style="min-height: calc(100vh - var(--header-height) - 100px);">
        <div class="container px-3 px-md-4">
            @isset($header)
                <x-slot name="header">
                    {{ $header }}
                </x-slot>
            @endisset

            <!-- Display All Errors -->
            @if ($errors->any())
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="alert alert-danger" role="alert">
                            <h5 class="fw-bold mb-2">
                                <i class="ti ti-alert-triangle me-2" aria-hidden="true"></i>{{ __('Please correct the following errors') }}:
                            </h5>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            {{ $slot }}
        </div>
    </main>

    <!-- Footer -->
    <footer class="guest-footer">
        <div class="container px-3 px-md-4">
            <span class="text-sm text-muted">© {{ date('Y') }} {{ config('app.name', 'Laravel') }}. {{ __('All rights reserved') }}.</span>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>

