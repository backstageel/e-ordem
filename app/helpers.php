<?php

if (! function_exists('set_current_role')) {
    /**
     * Set the current active role in the session.
     * If no role is provided, sets the default role based on user's roles.
     */
    function set_current_role(?string $role = null): void
    {
        $user = auth()->user();

        if (! $user) {
            return;
        }

        // If role is provided, validate it belongs to user
        if ($role) {
            if ($user->hasRole($role)) {
                session(['current_role' => $role]);
            }

            return;
        }

        // Get default role based on priority
        $defaultRole = get_default_role($user);
        if ($defaultRole) {
            session(['current_role' => $defaultRole]);
        }
    }
}

if (! function_exists('get_current_role')) {
    /**
     * Get the current active role from session.
     * If not set, returns the default role for the user.
     */
    function get_current_role(): ?string
    {
        $user = auth()->user();

        if (! $user) {
            return null;
        }

        $currentRole = session('current_role');

        // If current_role is set, validate it still belongs to user
        if ($currentRole && $user->hasRole($currentRole)) {
            return $currentRole;
        }

        // If not set or invalid, get default role and set it
        $defaultRole = get_default_role($user);
        if ($defaultRole) {
            session(['current_role' => $defaultRole]);
        }

        return $defaultRole;
    }
}

if (! function_exists('get_default_role')) {
    /**
     * Get the default role for a user based on priority.
     * Priority: super-admin > admin > secretariat > member > teacher
     */
    function get_default_role($user): ?string
    {
        if (! $user) {
            return null;
        }

        $rolePriority = ['super-admin', 'admin', 'secretariat', 'member', 'teacher'];

        foreach ($rolePriority as $role) {
            if ($user->hasRole($role)) {
                return $role;
            }
        }

        return null;
    }
}

if (! function_exists('get_user_roles')) {
    /**
     * Get all roles available for the authenticated user.
     */
    function get_user_roles(): array
    {
        $user = auth()->user();

        if (! $user) {
            return [];
        }

        return $user->getRoleNames()->toArray();
    }
}

if (! function_exists('dashboard_route')) {
    /**
     * Get the dashboard route based on the current active role.
     */
    function dashboard_route(): string
    {
        $user = auth()->user();

        if (! $user) {
            return route('login');
        }

        $currentRole = get_current_role();

        if (! $currentRole) {
            return route('login');
        }

        return match ($currentRole) {
            'admin', 'super-admin' => route('admin.dashboard.index'),
            'secretariat' => route('secretariat.dashboard.index'),
            'member' => route('member.dashboard.index'),
            'teacher' => route('teacher.dashboard'),
            default => route('admin.dashboard.index'),
        };
    }
}
