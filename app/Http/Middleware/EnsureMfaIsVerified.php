<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureMfaIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Add debug logging
        \Log::debug('MFA Middleware Check', [
            'user_id' => $user ? $user->id : null,
            'mfa_enabled' => $user ? $user->isMfaEnabled() : false,
            'mfa_verified_session' => session('mfa_verified') ? true : false,
            'mfa_verified_cookie' => $request->cookie('mfa_verified') ? true : false,
            'session_id' => session()->getId(),
            'session_data' => session()->all(),
            'request_path' => $request->path(),
            'request_method' => $request->method(),
            'cookies' => $request->cookies->all(),
        ]);

        // Check both session and cookie for MFA verification
        $mfaVerified = session('mfa_verified') || $request->cookie('mfa_verified');

        if ($user && $user->isMfaEnabled() && ! $mfaVerified) {
            \Log::debug('MFA Verification Required - Redirecting to MFA verification page');

            return redirect()->route('mfa.verify');
        }

        return $next($request);
    }
}
