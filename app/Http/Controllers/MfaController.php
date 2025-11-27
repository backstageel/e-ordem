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

    /**
     * Show the MFA setup page.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
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

    /**
     * Enable MFA for the user.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function enable(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
            'password' => 'required|string',
        ]);

        $user = Auth::user();

        if (! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['The provided password is incorrect.'],
            ]);
        }

        $secretKey = session('mfa_secret');

        if (! $secretKey || ! $this->mfaService->verifyCode($secretKey, $request->code)) {
            throw ValidationException::withMessages([
                'code' => ['The provided authentication code is invalid.'],
            ]);
        }

        $recoveryCodes = $this->mfaService->generateRecoveryCodes();
        $user->enableMfa($secretKey, $recoveryCodes);

        session()->forget('mfa_secret');

        return view('auth.mfa.recovery-codes', compact('recoveryCodes'));
    }

    /**
     * Disable MFA for the user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function disable(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $user = Auth::user();

        if (! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['The provided password is incorrect.'],
            ]);
        }

        $user->disableMfa();

        return redirect()->route('profile.edit')->with('status', 'mfa-disabled');
    }

    /**
     * Show the recovery codes.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showRecoveryCodes()
    {
        $user = Auth::user();

        if (! $user->isMfaEnabled()) {
            return redirect()->route('profile.edit');
        }

        $recoveryCodes = $user->two_factor_recovery_codes;

        return view('auth.mfa.recovery-codes', compact('recoveryCodes'));
    }

    /**
     * Regenerate recovery codes.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function regenerateRecoveryCodes(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $user = Auth::user();

        if (! Hash::check($request->password, $user->password)) {
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
