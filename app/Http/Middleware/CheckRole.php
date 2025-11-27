<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Check if user is authenticated
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        // Check if user has any of the required roles
        if (! auth()->user()->hasAnyRole($roles)) {
            // If it's an AJAX request, return JSON response
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Acesso negado. Você não tem o role necessário para acessar esta área.',
                    'error' => 'insufficient_role',
                ], 403);
            }

            // For web requests, redirect with error message
            return redirect()->back()
                ->with('error', 'Acesso negado. Você não tem o role necessário para acessar esta área.');
        }

        return $next($request);
    }
}
