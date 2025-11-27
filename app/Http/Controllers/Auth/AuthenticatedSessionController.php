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

        // Set current_role in session
        set_current_role();

        if ($user->isMfaEnabled()) {
            return redirect()->route('mfa.verify');
        }

        // Redirect to role-specific dashboard from Dashboard module
        return $this->redirectToDashboard($user);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->forget('mfa_verified');
        $request->session()->forget('current_role');

        $request->session()->regenerateToken();

        // Add debug logging for logout
        \Log::debug('User logged out - clearing MFA verification', [
            'session_id' => session()->getId(),
        ]);

        // Create response and clear the MFA verification cookie
        $response = redirect('/');
        $response->cookie('mfa_verified', null, -1);

        return $response;
    }

    /**
     * Redirect user to their role-specific dashboard.
     */
    private function redirectToDashboard($user): RedirectResponse
    {
        return redirect(dashboard_route());
    }
}
