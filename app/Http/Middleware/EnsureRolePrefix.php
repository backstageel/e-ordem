<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureRolePrefix
{
    /**
     * Routes that should be excluded from role prefix checking.
     *
     * @var array<string>
     */
    protected array $excludedRoutes = [
        'login',
        'register',
        'password.request',
        'password.reset',
        'password.email',
        'password.update',
        'verification.notice',
        'verification.verify',
        'verification.send',
        'logout',
        'profile.edit',
        'profile.update',
        'profile.destroy',
        'role.switch',
        'mfa.*',
        'guest.*',
        'api.*',
        'up',
    ];

    /**
     * Map user roles to route prefixes.
     *
     * @var array<string, string>
     */
    protected array $rolePrefixMap = [
        'admin' => 'admin',
        'super-admin' => 'admin',
        'secretariat' => 'secretariat',
        'member' => 'member',
        'teacher' => 'teacher',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Allow unauthenticated users to pass (they'll be handled by auth middleware)
        if (! Auth::check()) {
            return $next($request);
        }

        // Allow Livewire requests to pass (they handle their own routing)
        // Livewire sends X-Livewire header and uses /livewire/update endpoint
        if ($request->header('X-Livewire') === '1' ||
            str_contains($request->path(), 'livewire/update') ||
            str_contains($request->path(), 'livewire')) {
            return $next($request);
        }

        $user = Auth::user();
        $route = $request->route();

        // If route is not defined, allow to pass (will be handled by 404)
        if (! $route) {
            return $next($request);
        }

        $routeName = $route->getName();

        // Check if route should be excluded
        if ($this->shouldExcludeRoute($routeName, $request)) {
            return $next($request);
        }

        // Get the current active role from session
        $currentRole = get_current_role();

        // If no current role is set, allow to pass (will be handled by other middleware)
        if (! $currentRole) {
            return $next($request);
        }

        // Get the expected prefix for the current role
        $expectedPrefix = $this->getRolePrefix($currentRole);

        // If no prefix is expected (user has no valid role), allow to pass
        if (! $expectedPrefix) {
            return $next($request);
        }

        // Get the current route prefix
        $currentPrefix = $route->getPrefix();

        // Check if the route prefix matches the expected prefix
        if ($currentPrefix) {
            // Check if prefix exactly matches or starts with expected prefix followed by /
            if ($currentPrefix === $expectedPrefix || str_starts_with($currentPrefix, $expectedPrefix.'/')) {
                return $next($request);
            }
        }

        // Check if the route path starts with the expected prefix
        $path = $request->path();
        if ($path === $expectedPrefix || str_starts_with($path, $expectedPrefix.'/')) {
            return $next($request);
        }

        // If prefix doesn't match, redirect to user's dashboard
        return redirect(dashboard_route());
    }

    /**
     * Check if the route should be excluded from role prefix checking.
     */
    protected function shouldExcludeRoute(?string $routeName, Request $request): bool
    {
        if (! $routeName) {
            // Check if path matches excluded patterns
            $path = $request->path();
            if (str_starts_with($path, 'guest/') ||
                str_starts_with($path, 'api/') ||
                str_starts_with($path, 'login') ||
                str_starts_with($path, 'register') ||
                str_starts_with($path, 'password/') ||
                str_starts_with($path, 'email/verify') ||
                str_starts_with($path, 'profile') ||
                str_starts_with($path, 'mfa/') ||
                $path === 'up') {
                return true;
            }

            return false;
        }

        foreach ($this->excludedRoutes as $pattern) {
            if (str_contains($pattern, '*')) {
                $pattern = str_replace('*', '.*', $pattern);
                if (preg_match('/^'.$pattern.'$/', $routeName)) {
                    return true;
                }
            } elseif ($routeName === $pattern) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the route prefix for the given role.
     */
    protected function getRolePrefix(string $role): ?string
    {
        return $this->rolePrefixMap[$role] ?? null;
    }
}
