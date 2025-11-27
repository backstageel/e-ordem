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

    /**
     * Show the MFA verification page.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        return view('auth.mfa.verify');
    }

    /**
     * Verify the MFA code.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $user = Auth::user();

        // Add debug logging before verification
        \Log::debug('MFA Verification Attempt', [
            'user_id' => $user ? $user->id : null,
            'code_length' => strlen($request->code),
            'session_id' => session()->getId(),
            'session_data' => session()->all(),
        ]);

        if (strlen($request->code) === 6) {
            // Verify TOTP code
            $isValid = $this->mfaService->verifyCode($user->two_factor_secret, $request->code);
            \Log::debug('TOTP Code Verification', ['is_valid' => $isValid]);

            if (! $isValid) {
                throw ValidationException::withMessages([
                    'code' => ['The provided authentication code is invalid.'],
                ]);
            }
        } else {
            // Verify recovery code
            $isValid = $user->validateRecoveryCode($request->code);
            \Log::debug('Recovery Code Verification', ['is_valid' => $isValid]);

            if (! $isValid) {
                throw ValidationException::withMessages([
                    'code' => ['The provided recovery code is invalid.'],
                ]);
            }
        }

        // Set the session variable
        session(['mfa_verified' => true]);

        // Set current_role in session
        set_current_role();

        // Add debug logging after setting session variable
        \Log::debug('MFA Verification Successful', [
            'user_id' => $user->id,
            'session_id' => session()->getId(),
            'session_data' => session()->all(),
        ]);

        // Create a response with the intended redirect to role-specific dashboard
        $response = redirect()->intended(dashboard_route());

        // Add a cookie for MFA verification as a backup mechanism
        $response->cookie('mfa_verified', true, 60 * 24); // 24 hours

        \Log::debug('Setting MFA verification cookie', [
            'cookie_name' => 'mfa_verified',
            'cookie_value' => true,
            'cookie_lifetime' => 60 * 24,
        ]);

        return $response;
    }
}
