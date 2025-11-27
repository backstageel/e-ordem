<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        $messages = [
            Password::RESET_LINK_SENT => 'Enviamos por email o link para redefinir a sua senha.',
            Password::RESET_THROTTLED => 'Aguarde antes de tentar novamente.',
            Password::INVALID_USER => 'Não conseguimos encontrar um utilizador com este endereço de email.',
        ];

        $message = $messages[$status] ?? 'Ocorreu um erro. Por favor, tente novamente.';

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', $message)
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => $message]);
    }
}
