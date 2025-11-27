@props([
    'title' => null,
    'breadcrumbs' => null,
])

@php
    // Determine HTML attributes based on route
    $htmlAttributes = [];
    $htmlLang = 'en';

    if (Route::is(['layout-dark'])) {
        $htmlAttributes['data-bs-theme'] = 'dark';
    }
    if (Route::is(['layout-hidden'])) {
        $htmlAttributes['data-layout'] = 'hidden';
    }
    if (Route::is(['layout-hover-view'])) {
        $htmlAttributes['data-layout'] = 'hoverview';
    }
    if (Route::is(['layout-mini'])) {
        $htmlAttributes['data-layout'] = 'mini';
    }
    if (Route::is(['layout-rtl'])) {
        $htmlAttributes['dir'] = 'rtl';
    }
    if (Route::is(['layout-full-width'])) {
        $htmlAttributes['data-layout'] = 'full-width';
    }

    // Determine body class
    $bodyClass = Route::is(['layout-mini']) ? 'mini-sidebar' : '';

    // Determine wrapper class based on route
    $authRoutes = [
        'login-basic', 'login-illustration', 'login-cover', 'login',
        'register-basic', 'register-illustration', 'register-cover',
        'forgot-password-basic', 'forgot-password-illustration', 'forgot-password-cover',
        'reset-password-basic', 'reset-password-illustration', 'reset-password-cover',
        'email-verification-basic', 'email-verification-illustration', 'email-verification-cover',
        'success-basic', 'success-illustration', 'success-cover',
        'two-step-verification-basic', 'two-step-verification-illustration', 'two-step-verification-cover',
        'lock-screen', 'error-404', 'error-500', 'coming-soon', 'under-maintenance'
    ];

    $isAuthRoute = Route::is($authRoutes);
    $isLoginRoute = Route::is(['login']);
    $isComingSoonRoute = Route::is(['coming-soon', 'under-maintenance']);

    $wrapperClass = 'main-wrapper';
    if ($isLoginRoute) {
        $wrapperClass = 'main-wrapper auth-bg auth-bg-custom position-relative overflow-hidden';
    } elseif ($isComingSoonRoute) {
        $wrapperClass = 'main-wrapper auth-bg';
    } elseif ($isAuthRoute) {
        $wrapperClass = 'main-wrapper auth-bg position-relative overflow-hidden';
    }

    $showHeaderSidebar = !$isAuthRoute;
@endphp

<!DOCTYPE html>
<html lang="{{ $htmlLang }}" @foreach($htmlAttributes as $key => $value) {{ $key }}="{{ $value }}" @endforeach>

@include('layout.partials.title-meta')

<body @if($bodyClass) class="{{ $bodyClass }}" @endif>

    <!-- Start Main Wrapper -->
    <div class="{{ $wrapperClass }}">

        @if($showHeaderSidebar)
            @include('layout.partials.header')
            @include('layout.partials.sidebar')
        @endif

        {{ $slot }}

        <x-modal-popup />

    </div>
    <!-- End Main Wrapper -->

    @include('layout.partials.footer-scripts')

</body>
</html>

