@props([
    'title' => null,
])

@php
    // Determine HTML attributes based on route
    $htmlAttributes = ['data-layout' => 'full-width'];
    $htmlLang = 'pt';

    // Determine wrapper class for guest pages
    $wrapperClass = 'main-wrapper';
@endphp

<!DOCTYPE html>
<html lang="{{ $htmlLang }}" @foreach($htmlAttributes as $key => $value) {{ $key }}="{{ $value }}" @endforeach>

@include('layout.partials.title-meta')

<body>
    <!-- Start Main Wrapper -->
    <div class="{{ $wrapperClass }}">

        <!-- Guest Top Navbar -->
        <header class="navbar-header">
            <div class="page-container topbar-menu">
                <div class="d-flex align-items-center gap-2">
                    <!-- Logo -->
                    <a href="{{ route('login') }}" class="logo d-flex align-items-center">
                        <span class="logo-light">
                            <span class="logo-lg"><img src="{{ asset('build/img/ordem-logo.png') }}" alt="Ordem dos Médicos de Moçambique" height="40"></span>
                        </span>
                    </a>
                </div>

                <div class="d-flex align-items-center">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="ti ti-login me-1"></i>Login
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <div class="page-wrapper">
            <!-- Start Content -->
            <div class="content pb-0">
                {{ $slot }}
            </div>
            <!-- End Content -->
        </div>

        <x-modal-popup />

    </div>
    <!-- End Main Wrapper -->

    @include('layout.partials.footer-scripts')

</body>
</html>
