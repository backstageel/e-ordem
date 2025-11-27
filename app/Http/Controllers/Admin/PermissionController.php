<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of permissions.
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $search = $request->input('search');
        $group = $request->input('group');

        // Build query
        $query = Permission::withCount('roles')->latest();

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        // Get paginated results
        $permissions = $query->paginate(50);

        // Group permissions for display
        $groupedPermissions = $permissions->groupBy(function ($permission) {
            return explode('.', $permission->name)[0] ?? 'other';
        });

        // Get all available groups
        $availableGroups = Permission::all()->map(function ($permission) {
            return explode('.', $permission->name)[0] ?? 'other';
        })->unique()->sort()->values();

        // Filter by group if specified
        if ($group && $availableGroups->contains($group)) {
            $groupedPermissions = $groupedPermissions->filter(function ($permissions, $key) use ($group) {
                return $key === $group;
            });
        }

        // Log the view action

        return view('admin.permissions.index', compact('permissions', 'groupedPermissions', 'availableGroups'));
    }

    /**
     * Show the form for creating a new permission.
     */
    public function create()
    {
        return view('admin.permissions.create');
    }

    /**
     * Store a newly created permission in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name'],
            'guard_name' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string', 'max:500'],
        ]);

        $permission = Permission::create([
            'name' => $request->name,
            'guard_name' => $request->guard_name ?? 'web',
            'display_name' => \App\Models\Permission::deriveDisplayName($request->name),
            'category' => \App\Models\Permission::deriveCategory($request->name),
        ]);

        // Log the action

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission created successfully.');
    }

    /**
     * Display the specified permission.
     */
    public function show(Permission $permission)
    {
        $permission->load('roles');

        // Log the view action

        return view('admin.permissions.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified permission.
     */
    public function edit(Permission $permission)
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    /**
     * Update the specified permission in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name,'.$permission->id],
            'guard_name' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string', 'max:500'],
        ]);

        $permission->update([
            'name' => $request->name,
            'guard_name' => $request->guard_name ?? 'web',
            'display_name' => \App\Models\Permission::deriveDisplayName($request->name),
            'category' => \App\Models\Permission::deriveCategory($request->name),
        ]);

        // Log the action

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission updated successfully.');
    }

    /**
     * Remove the specified permission from storage.
     */
    public function destroy(Permission $permission)
    {
        $permissionName = $permission->name;
        $permissionId = $permission->id;

        // Check if permission can be deleted
        $this->validateDeletion($permission);

        $permission->delete();

        // Log the action

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission deleted successfully.');
    }

    /**
     * Validate if the permission can be deleted.
     */
    private function validateDeletion(Permission $permission): void
    {
        // Check if permission is assigned to any roles
        if ($permission->roles()->count() > 0) {
            throw new \Exception('Cannot delete permission that is assigned to roles. Please remove from roles first.');
        }

        // Check if permission is assigned to any users directly
        if ($permission->users()->count() > 0) {
            throw new \Exception('Cannot delete permission that is assigned to users. Please remove from users first.');
        }
    }

    /**
     * Bulk assign permissions to a role.
     */
    public function assignToRole(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => ['required', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $permissions = Permission::whereIn('id', $request->permissions)->get();
        $role->syncPermissions($permissions);

        return redirect()->back()
            ->with('success', 'Permissions assigned to role successfully.');
    }

    /**
     * Bulk remove permissions from a role.
     */
    public function removeFromRole(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => ['required', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $permissions = Permission::whereIn('id', $request->permissions)->get();
        $role->revokePermissionTo($permissions);

        return redirect()->back()
            ->with('success', 'Permissions removed from role successfully.');
    }
}
