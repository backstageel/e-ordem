<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class RoleSwitchController extends Controller
{
    /**
     * Switch the current active role for the authenticated user.
     */
    public function switch(Request $request): RedirectResponse
    {
        $request->validate([
            'role' => ['required', 'string'],
        ]);

        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        $requestedRole = $request->input('role');

        // Verify that the user has the requested role
        if (! $user->hasRole($requestedRole)) {
            throw ValidationException::withMessages([
                'role' => ['Você não tem permissão para usar este role.'],
            ]);
        }

        // Set the new current role in session
        set_current_role($requestedRole);

        // Redirect to the dashboard for the new role
        return redirect(dashboard_route())
            ->with('success', __('Role alterado com sucesso.'));
    }
}
