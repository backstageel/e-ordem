@php
    $user = Auth::user();
    $primaryRole = $user->roles->first()?->name ?? 'guest';

    // Map roles to sidebar components (only map existing sidebars)
    $sidebarMap = [
        'admin' => 'layouts.admin-sidebar',
        'super-admin' => 'layouts.admin-sidebar',
        'secretariat' => 'layouts.admin-sidebar', // Use admin sidebar for now
        'member' => 'layouts.member-sidebar',
        'treasury' => 'layouts.admin-sidebar', // Use admin sidebar for now
        'council' => 'layouts.admin-sidebar', // Use admin sidebar for now
        'auditor' => 'layouts.admin-sidebar', // Use admin sidebar for now (read-only)
        'evaluator' => 'layouts.admin-sidebar', // Use admin sidebar for now
        'supervisor' => 'layouts.admin-sidebar', // Use admin sidebar for now
        'teacher' => 'layouts.member-sidebar', // Use member sidebar for now
        'candidate' => 'layouts.member-sidebar', // Use member sidebar for now
    ];

    // Fallback to admin-sidebar if role not mapped (should not happen in production)
    $sidebarComponent = $sidebarMap[$primaryRole] ?? 'layouts.admin-sidebar';
@endphp

<x-dynamic-component :component="$sidebarComponent" />

