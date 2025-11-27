@props([
    'title' => null,
])

@php
    // Determine HTML attributes based on route
    $htmlAttributes = [];
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
        <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom" style="height: 60px;">
            <div class="container-fluid">
                <a class="navbar-brand d-flex align-items-center" href="{{ route('login') }}">
                    <img src="{{ asset('build/img/ordem-logo.png') }}" alt="Ordem dos Médicos de Moçambique" height="40" class="me-2">
                    <span class="fw-bold text-dark d-none d-md-inline">e-Ordem</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#guestNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="guestNavbar">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="ti ti-login me-1"></i>Login
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <div class="page-wrapper">
            <div class="content pb-0">
                {{ $slot }}
            </div>
        </div>

        <x-modal-popup />

    </div>
    <!-- End Main Wrapper -->

    @include('layout.partials.footer-scripts')

</body>
</html>
