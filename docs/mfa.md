# Multi-Factor Authentication (MFA) Implementation Plan

## Overview

This document outlines the implementation plan for adding Multi-Factor Authentication (MFA) to the Ordem dos Médicos de Moçambique (OrMM) management system. MFA adds an additional layer of security by requiring users to provide two or more verification factors to gain access to the system, significantly reducing the risk of unauthorized access even if passwords are compromised.

## Current Authentication System

The OrMM system currently uses Laravel's standard authentication system (Laravel Breeze) with the following features:
- Email and password authentication
- Password reset functionality
- Email verification
- Remember me functionality
- Role-based access control (admin, member, teacher)

## MFA Implementation Options

After evaluating several options for implementing MFA in Laravel, we have identified the following approaches:

### Option 1: Laravel Fortify

**Description**: Laravel Fortify is an official Laravel package that provides frontend-agnostic authentication scaffolding, including two-factor authentication.

**Pros**:
- Official Laravel package with good documentation and community support
- Seamless integration with Laravel's authentication system
- Provides built-in two-factor authentication using TOTP (Time-based One-Time Password)
- Includes QR code generation for easy setup with authenticator apps
- Supports recovery codes for emergency access

**Cons**:
- Requires significant changes to the current authentication flow
- May require additional frontend work to integrate with the existing UI
- Adds dependencies that might not be needed for other features

### Option 2: Custom Implementation with Google Authenticator

**Description**: A custom implementation using the `pragmarx/google2fa` package to add TOTP-based two-factor authentication.

**Pros**:
- More flexible and customizable
- Lighter weight than Fortify (only adds what's needed)
- Can be integrated with the existing authentication flow with minimal changes
- Works well with authenticator apps like Google Authenticator, Authy, etc.

**Cons**:
- Requires more manual implementation work
- No built-in recovery code system (would need to be implemented)
- Less official documentation and support

### Option 3: SMS-Based Verification

**Description**: Implementing SMS-based verification using services like Twilio or local SMS gateways.

**Pros**:
- Doesn't require users to install additional apps
- Familiar to many users
- Already using Twilio for notifications (as mentioned in the project documentation)

**Cons**:
- Additional cost per SMS
- Dependent on mobile network availability
- Less secure than TOTP-based solutions
- Requires phone number verification and management

## Recommended Approach

Based on the evaluation of the options and considering the project's requirements, we recommend **Option 2: Custom Implementation with Google Authenticator** for the following reasons:

1. It provides a good balance between security and user experience
2. It minimizes changes to the existing authentication system
3. It's lightweight and focused on just the MFA functionality needed
4. It allows for customization to match the system's UI and UX requirements
5. It works well with the existing Laravel Breeze authentication

## Implementation Plan

### 1. Install Required Packages

```bash
composer require pragmarx/google2fa
composer require bacon/bacon-qr-code
```

### 2. Create Database Migrations

Create a migration to add MFA-related fields to the users table:

```bash
sail artisan make:migration add_two_factor_columns_to_users_table
```

Update the migration file:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('two_factor_secret')
                ->after('password')
                ->nullable();
                
            $table->text('two_factor_recovery_codes')
                ->after('two_factor_secret')
                ->nullable();
                
            $table->boolean('two_factor_enabled')
                ->after('two_factor_recovery_codes')
                ->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'two_factor_secret',
                'two_factor_recovery_codes',
                'two_factor_enabled',
            ]);
        });
    }
};
```

Run the migration:

```bash
sail artisan migrate
```

### 3. Create MFA Service

Create a service class to handle MFA operations:

```php
<?php

namespace App\Services;

use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Support\Str;

class MfaService
{
    protected $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    public function generateSecretKey()
    {
        return $this->google2fa->generateSecretKey();
    }

    public function generateQrCode($email, $secretKey)
    {
        $appName = config('app.name');
        $qrCodeUrl = $this->google2fa->getQRCodeUrl($appName, $email, $secretKey);
        
        $writer = new Writer(
            new ImageRenderer(
                new RendererStyle(200),
                new SvgImageBackEnd()
            )
        );
        
        return $writer->writeString($qrCodeUrl);
    }

    public function verifyCode($secretKey, $code)
    {
        return $this->google2fa->verifyKey($secretKey, $code);
    }

    public function generateRecoveryCodes()
    {
        $recoveryCodes = [];
        
        for ($i = 0; $i < 8; $i++) {
            $recoveryCodes[] = Str::random(10);
        }
        
        return $recoveryCodes;
    }
}
```

### 4. Update User Model

Update the User model to handle MFA-related attributes:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = false;

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'two_factor_enabled' => 'boolean',
        'two_factor_recovery_codes' => 'array',
    ];

    public function isMfaEnabled()
    {
        return $this->two_factor_enabled;
    }

    public function enableMfa($secretKey, $recoveryCodes)
    {
        $this->two_factor_secret = $secretKey;
        $this->two_factor_recovery_codes = $recoveryCodes;
        $this->two_factor_enabled = true;
        $this->save();
    }

    public function disableMfa()
    {
        $this->two_factor_secret = null;
        $this->two_factor_recovery_codes = null;
        $this->two_factor_enabled = false;
        $this->save();
    }

    public function validateRecoveryCode($code)
    {
        if (!$this->two_factor_recovery_codes) {
            return false;
        }

        $recoveryCodes = $this->two_factor_recovery_codes;
        
        $position = array_search($code, $recoveryCodes);
        
        if ($position !== false) {
            unset($recoveryCodes[$position]);
            $this->two_factor_recovery_codes = array_values($recoveryCodes);
            $this->save();
            return true;
        }
        
        return false;
    }
}
```

### 5. Create MFA Controllers

Create controllers to handle MFA setup and verification:

```bash
sail artisan make:controller MfaController
```

Update the controller:

```php
<?php

namespace App\Http\Controllers;

use App\Services\MfaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class MfaController extends Controller
{
    protected $mfaService;

    public function __construct(MfaService $mfaService)
    {
        $this->mfaService = $mfaService;
    }

    public function setup()
    {
        $user = Auth::user();
        
        if ($user->isMfaEnabled()) {
            return redirect()->route('profile.edit')->with('status', 'mfa-already-enabled');
        }
        
        $secretKey = $this->mfaService->generateSecretKey();
        $qrCode = $this->mfaService->generateQrCode($user->email, $secretKey);
        
        session(['mfa_secret' => $secretKey]);
        
        return view('auth.mfa.setup', compact('qrCode', 'secretKey'));
    }

    public function enable(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
            'password' => 'required|string',
        ]);
        
        $user = Auth::user();
        
        if (!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['The provided password is incorrect.'],
            ]);
        }
        
        $secretKey = session('mfa_secret');
        
        if (!$secretKey || !$this->mfaService->verifyCode($secretKey, $request->code)) {
            throw ValidationException::withMessages([
                'code' => ['The provided authentication code is invalid.'],
            ]);
        }
        
        $recoveryCodes = $this->mfaService->generateRecoveryCodes();
        $user->enableMfa($secretKey, $recoveryCodes);
        
        session()->forget('mfa_secret');
        
        return view('auth.mfa.recovery-codes', compact('recoveryCodes'));
    }

    public function disable(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);
        
        $user = Auth::user();
        
        if (!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['The provided password is incorrect.'],
            ]);
        }
        
        $user->disableMfa();
        
        return redirect()->route('profile.edit')->with('status', 'mfa-disabled');
    }

    public function showRecoveryCodes()
    {
        $user = Auth::user();
        
        if (!$user->isMfaEnabled()) {
            return redirect()->route('profile.edit');
        }
        
        $recoveryCodes = $user->two_factor_recovery_codes;
        
        return view('auth.mfa.recovery-codes', compact('recoveryCodes'));
    }

    public function regenerateRecoveryCodes(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);
        
        $user = Auth::user();
        
        if (!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['The provided password is incorrect.'],
            ]);
        }
        
        $recoveryCodes = $this->mfaService->generateRecoveryCodes();
        $user->two_factor_recovery_codes = $recoveryCodes;
        $user->save();
        
        return view('auth.mfa.recovery-codes', compact('recoveryCodes'));
    }
}
```

### 6. Create MFA Middleware

Create a middleware to handle MFA verification during login:

```bash
sail artisan make:middleware EnsureMfaIsVerified
```

Update the middleware:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureMfaIsVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        if ($user && $user->isMfaEnabled() && !session('mfa_verified')) {
            return redirect()->route('mfa.verify');
        }
        
        return $next($request);
    }
}
```

Register the middleware in `app/Http/Kernel.php`:

```php
protected $middlewareAliases = [
    // ... other middleware
    'mfa.verified' => \App\Http\Middleware\EnsureMfaIsVerified::class,
];

protected $middlewareGroups = [
    'web' => [
        // ... other middleware
    ],
    
    'api' => [
        // ... other middleware
    ],
];
```

### 7. Create MFA Verification Controller

```bash
sail artisan make:controller MfaVerificationController
```

Update the controller:

```php
<?php

namespace App\Http\Controllers;

use App\Services\MfaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class MfaVerificationController extends Controller
{
    protected $mfaService;

    public function __construct(MfaService $mfaService)
    {
        $this->mfaService = $mfaService;
    }

    public function show()
    {
        return view('auth.mfa.verify');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);
        
        $user = Auth::user();
        
        if (strlen($request->code) === 6) {
            // Verify TOTP code
            if (!$this->mfaService->verifyCode($user->two_factor_secret, $request->code)) {
                throw ValidationException::withMessages([
                    'code' => ['The provided authentication code is invalid.'],
                ]);
            }
        } else {
            // Verify recovery code
            if (!$user->validateRecoveryCode($request->code)) {
                throw ValidationException::withMessages([
                    'code' => ['The provided recovery code is invalid.'],
                ]);
            }
        }
        
        session(['mfa_verified' => true]);
        
        return redirect()->intended(route('dashboard'));
    }
}
```

### 8. Update Authentication Flow

Modify the `AuthenticatedSessionController` to handle MFA verification:

```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();
        
        // If user has MFA enabled, redirect to MFA verification
        $user = auth()->user();
        
        if ($user->isMfaEnabled()) {
            return redirect()->route('mfa.verify');
        }

        // Redirect to role-specific dashboard
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('member')) {
            return redirect()->route('member.dashboard');
        } elseif ($user->hasRole('teacher')) {
            return redirect()->route('teacher.dashboard');
        }

        // Fallback to the general dashboard
        return redirect()->route('dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->forget('mfa_verified');

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
```

### 9. Add MFA Routes

Add the following routes to `routes/web.php`:

```php
// MFA routes
Route::middleware(['auth'])->group(function () {
    Route::get('/mfa/setup', [App\Http\Controllers\MfaController::class, 'setup'])->name('mfa.setup');
    Route::post('/mfa/enable', [App\Http\Controllers\MfaController::class, 'enable'])->name('mfa.enable');
    Route::post('/mfa/disable', [App\Http\Controllers\MfaController::class, 'disable'])->name('mfa.disable');
    Route::get('/mfa/recovery-codes', [App\Http\Controllers\MfaController::class, 'showRecoveryCodes'])->name('mfa.recovery-codes');
    Route::post('/mfa/recovery-codes', [App\Http\Controllers\MfaController::class, 'regenerateRecoveryCodes'])->name('mfa.regenerate-recovery-codes');
    
    Route::get('/mfa/verify', [App\Http\Controllers\MfaVerificationController::class, 'show'])->name('mfa.verify');
    Route::post('/mfa/verify', [App\Http\Controllers\MfaVerificationController::class, 'verify'])->name('mfa.verify.store');
});
```

Update the middleware for protected routes:

```php
// Admin Dashboard Route
Route::get('/admin/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'mfa.verified', 'admin'])
    ->name('admin.dashboard');

// Member Dashboard Route
Route::get('/member/dashboard', [App\Http\Controllers\Member\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'mfa.verified', 'member'])
    ->name('member.dashboard');

// Teacher Dashboard Route
Route::get('/teacher/dashboard', [App\Http\Controllers\Teacher\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'mfa.verified', 'teacher'])
    ->name('teacher.dashboard');
```

### 10. Create MFA Views

Create the following Blade views:

#### MFA Setup View (`resources/views/auth/mfa/setup.blade.php`):

```blade
<x-layouts.app>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Set Up Two-Factor Authentication') }}</div>

                    <div class="card-body">
                        <p>{{ __('Two-factor authentication adds an additional layer of security to your account.') }}</p>
                        
                        <div class="alert alert-info">
                            <p>{{ __('To enable two-factor authentication, scan the following QR code using your phone\'s authenticator application (like Google Authenticator, Authy, or Microsoft Authenticator).') }}</p>
                        </div>
                        
                        <div class="text-center my-4">
                            {!! $qrCode !!}
                        </div>
                        
                        <div class="alert alert-warning">
                            <p>{{ __('If you cannot scan the QR code, you can manually set up your authenticator app using this code:') }}</p>
                            <div class="font-monospace text-center p-2 bg-light">{{ $secretKey }}</div>
                        </div>
                        
                        <form method="POST" action="{{ route('mfa.enable') }}">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="code" class="form-label">{{ __('Authentication Code') }}</label>
                                <input id="code" type="text" class="form-control @error('code') is-invalid @enderror" name="code" required autocomplete="off" autofocus>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">{{ __('Enter the 6-digit code from your authenticator app.') }}</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">{{ __('Current Password') }}</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">{{ __('Confirm your password to enable two-factor authentication.') }}</div>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('profile.edit') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                                <button type="submit" class="btn btn-primary">{{ __('Enable Two-Factor Authentication') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
```

#### MFA Verification View (`resources/views/auth/mfa/verify.blade.php`):

```blade
<x-layouts.app>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Two-Factor Authentication') }}</div>

                    <div class="card-body">
                        <p>{{ __('Please enter the authentication code from your authenticator app.') }}</p>
                        
                        <form method="POST" action="{{ route('mfa.verify.store') }}">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="code" class="form-label">{{ __('Authentication Code') }}</label>
                                <input id="code" type="text" class="form-control @error('code') is-invalid @enderror" name="code" required autocomplete="off" autofocus>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <p>{{ __('If you lost access to your authenticator app, you can use a recovery code.') }}</p>
                                <a href="#" onclick="toggleRecoveryCodeForm(); return false;">{{ __('Use a recovery code') }}</a>
                            </div>
                            
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">{{ __('Verify') }}</button>
                            </div>
                        </form>
                        
                        <form id="recovery-code-form" method="POST" action="{{ route('mfa.verify.store') }}" class="d-none mt-4">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="recovery-code" class="form-label">{{ __('Recovery Code') }}</label>
                                <input id="recovery-code" type="text" class="form-control" name="code" required autocomplete="off">
                                <div class="form-text">{{ __('Enter one of your recovery codes.') }}</div>
                            </div>
                            
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">{{ __('Verify') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function toggleRecoveryCodeForm() {
            const normalForm = document.querySelector('form:not(#recovery-code-form)');
            const recoveryForm = document.getElementById('recovery-code-form');
            
            if (recoveryForm.classList.contains('d-none')) {
                normalForm.classList.add('d-none');
                recoveryForm.classList.remove('d-none');
            } else {
                recoveryForm.classList.add('d-none');
                normalForm.classList.remove('d-none');
            }
        }
    </script>
</x-layouts.app>
```

#### Recovery Codes View (`resources/views/auth/mfa/recovery-codes.blade.php`):

```blade
<x-layouts.app>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Two-Factor Authentication Recovery Codes') }}</div>

                    <div class="card-body">
                        <div class="alert alert-warning">
                            <p>{{ __('Store these recovery codes in a secure password manager. They can be used to recover access to your account if you lose your two-factor authentication device.') }}</p>
                            <p class="mb-0">{{ __('Each code can only be used once.') }}</p>
                        </div>
                        
                        <div class="bg-light p-3 font-monospace mb-4">
                            @foreach($recoveryCodes as $code)
                                <div>{{ $code }}</div>
                            @endforeach
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('profile.edit') }}" class="btn btn-primary">{{ __('Done') }}</a>
                            <form method="POST" action="{{ route('mfa.regenerate-recovery-codes') }}" class="d-inline">
                                @csrf
                                <button type="button" class="btn btn-secondary" onclick="confirmRegenerate()">
                                    {{ __('Regenerate Recovery Codes') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="regenerateModal" tabindex="-1" aria-labelledby="regenerateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="regenerateModalLabel">{{ __('Regenerate Recovery Codes') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('Are you sure you want to regenerate your recovery codes? All current recovery codes will be invalidated.') }}</p>
                    <form id="regenerateForm" method="POST" action="{{ route('mfa.regenerate-recovery-codes') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('Current Password') }}</label>
                            <input id="password" type="password" class="form-control" name="password" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-danger" onclick="document.getElementById('regenerateForm').submit()">
                        {{ __('Regenerate') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function confirmRegenerate() {
            const modal = new bootstrap.Modal(document.getElementById('regenerateModal'));
            modal.show();
        }
    </script>
</x-layouts.app>
```

### 11. Update Profile View

Add MFA options to the user profile page:

```blade
<div class="card mb-4">
    <div class="card-header">{{ __('Two-Factor Authentication') }}</div>
    
    <div class="card-body">
        @if(auth()->user()->isMfaEnabled())
            <p>{{ __('You have enabled two-factor authentication.') }}</p>
            
            <div class="d-flex gap-2">
                <a href="{{ route('mfa.recovery-codes') }}" class="btn btn-secondary">
                    {{ __('View Recovery Codes') }}
                </a>
                
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#disableMfaModal">
                    {{ __('Disable Two-Factor Authentication') }}
                </button>
            </div>
        @else
            <p>{{ __('You have not enabled two-factor authentication.') }}</p>
            <p>{{ __('When two-factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone\'s authenticator app.') }}</p>
            
            <a href="{{ route('mfa.setup') }}" class="btn btn-primary">
                {{ __('Enable Two-Factor Authentication') }}
            </a>
        @endif
    </div>
</div>

<!-- Disable MFA Modal -->
@if(auth()->user()->isMfaEnabled())
<div class="modal fade" id="disableMfaModal" tabindex="-1" aria-labelledby="disableMfaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="disableMfaModalLabel">{{ __('Disable Two-Factor Authentication') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('mfa.disable') }}">
                @csrf
                <div class="modal-body">
                    <p>{{ __('Are you sure you want to disable two-factor authentication? This will make your account less secure.') }}</p>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">{{ __('Current Password') }}</label>
                        <input id="password" type="password" class="form-control" name="password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-danger">{{ __('Disable') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
```

### 12. Update Kernel.php

Update the `app/Http/Kernel.php` file to add the MFA middleware to the appropriate middleware groups:

```php
protected $middlewareGroups = [
    'web' => [
        // ... other middleware
    ],
    
    'api' => [
        // ... other middleware
    ],
];

protected $middlewareAliases = [
    // ... other middleware
    'mfa.verified' => \App\Http\Middleware\EnsureMfaIsVerified::class,
];

protected $routeMiddleware = [
    // ... other middleware
];
```

### 13. Add MFA Configuration

Create a configuration file for MFA settings:

```bash
sail artisan make:config mfa
```

Update the configuration file:

```php
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | MFA Settings
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration settings for Multi-Factor Authentication.
    |
    */

    // Whether MFA is enabled globally for the application
    'enabled' => env('MFA_ENABLED', true),
    
    // Whether MFA is required for all users or optional
    'required' => env('MFA_REQUIRED', false),
    
    // The number of recovery codes to generate for each user
    'recovery_codes_count' => 8,
    
    // The length of each recovery code
    'recovery_code_length' => 10,
    
    // The window in which a TOTP code is valid (in seconds)
    'window' => 30,
];
```

## User Experience Flow

1. **Setup Flow**:
   - User navigates to their profile settings
   - User clicks "Enable Two-Factor Authentication"
   - User is shown a QR code to scan with their authenticator app
   - User enters the code from their authenticator app to verify setup
   - User is shown recovery codes and instructed to save them securely
   - MFA is now enabled for the user's account

2. **Login Flow**:
   - User enters email and password
   - If credentials are valid and MFA is enabled for the user, they are redirected to the MFA verification page
   - User enters the code from their authenticator app
   - If the code is valid, the user is logged in and redirected to their dashboard
   - If the user has lost access to their authenticator app, they can use a recovery code instead

3. **Disable Flow**:
   - User navigates to their profile settings
   - User clicks "Disable Two-Factor Authentication"
   - User confirms by entering their password
   - MFA is disabled for the user's account

## Security Considerations

1. **Recovery Codes**: Recovery codes are essential for users who lose access to their authenticator app. These should be stored securely and only used when necessary.

2. **Backup Procedures**: Administrators should have procedures in place to help users who have lost both their authenticator app and recovery codes.

3. **Phishing Protection**: Users should be educated about the importance of only entering their MFA codes on legitimate OrMM websites.

4. **Rate Limiting**: Implement rate limiting on MFA verification attempts to prevent brute force attacks.

5. **Session Management**: Ensure that MFA verification is required for each new session, not just the first login.

## Implementation Timeline

1. **Phase 1 (Week 1)**: Database migrations and backend implementation
   - Create database migrations
   - Implement MFA service and controllers
   - Update authentication flow

2. **Phase 2 (Week 2)**: Frontend implementation and testing
   - Create MFA views
   - Update profile page
   - Test MFA flow with different user roles

3. **Phase 3 (Week 3)**: Documentation and deployment
   - Create user documentation
   - Train administrators
   - Deploy to production

## Conclusion

This implementation plan provides a comprehensive approach to adding Multi-Factor Authentication to the OrMM system. By following this plan, the system will have a robust MFA solution that enhances security while maintaining a good user experience.

The custom implementation with Google Authenticator provides a good balance between security, usability, and maintainability, and aligns well with the existing Laravel Breeze authentication system.
