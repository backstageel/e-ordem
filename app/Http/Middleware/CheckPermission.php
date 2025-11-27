<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        // Check if user is authenticated
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Super-admin has access to everything
        if ($user->hasRole('super-admin')) {
            return $next($request);
        }

        // Support multiple permissions with OR operator (|)
        $permissions = explode('|', $permission);
        $hasPermission = false;

        foreach ($permissions as $perm) {
            $perm = trim($perm);
            if ($user->can($perm)) {
                $hasPermission = true;
                break;
            }
        }

        // Check if user has any of the required permissions
        if (! $hasPermission) {
            // If it's an AJAX request, return JSON response
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Acesso negado. Você não tem permissão para realizar esta ação.',
                    'error' => 'insufficient_permissions',
                ], 403);
            }

            // For web requests, redirect with error message
            return redirect()->back()
                ->with('error', 'Acesso negado. Você não tem permissão para realizar esta ação.');
        }

        return $next($request);
    }
}
