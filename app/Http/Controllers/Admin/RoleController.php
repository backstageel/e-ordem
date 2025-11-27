<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\CreateRoleAction;
use App\Actions\Admin\DeleteRoleAction;
use App\Actions\Admin\UpdateRoleAction;
use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of roles.
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $search = $request->input('search');

        // Build query
        $query = Role::withCount('users')->latest();

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        // Get paginated results
        $roles = $query->paginate(20);

        // Log the view action

        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new role.
     */
    public function create()
    {
        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode('.', $permission->name)[0] ?? 'other';
        });

        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created role in storage.
     */
    public function store(Request $request)
    {
        try {
            $action = new CreateRoleAction;
            $role = $action->execute($request->all());

            return redirect()->route('admin.roles.index')
                ->with('success', 'Role created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while creating the role: '.$e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified role.
     */
    public function show(Role $role)
    {
        $role->load('permissions', 'users');

        return view('admin.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(Role $role)
    {
        $role->load('permissions');
        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode('.', $permission->name)[0] ?? 'other';
        });

        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified role in storage.
     */
    public function update(Request $request, Role $role)
    {
        try {
            $action = new UpdateRoleAction;
            $action->execute($role, $request->all());

            return redirect()->route('admin.roles.index')
                ->with('success', 'Role updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while updating the role: '.$e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified role from storage.
     */
    public function destroy(Role $role)
    {
        try {
            $action = new DeleteRoleAction;
            $action->execute($role);

            return redirect()->route('admin.roles.index')
                ->with('success', 'Role deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while deleting the role: '.$e->getMessage());
        }
    }

    /**
     * Display a listing of permissions.
     */
    public function permissions()
    {
        $permissions = Permission::withCount('roles')->get()->groupBy(function ($permission) {
            return explode('.', $permission->name)[0] ?? 'other';
        });

        // Log the view action

        return view('admin.roles.permissions', compact('permissions'));
    }

    /**
     * Show the form for creating a new permission.
     */
    public function createPermission()
    {
        return view('admin.roles.create-permission');
    }

    /**
     * Store a newly created permission in storage.
     */
    public function storePermission(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name'],
            'guard_name' => ['sometimes', 'string', 'max:255'],
        ]);

        $permission = Permission::create([
            'name' => $request->name,
            'guard_name' => $request->guard_name ?? 'web',
        ]);

        return redirect()->route('admin.roles.permissions')
            ->with('success', 'Permission created successfully.');
    }

    /**
     * Show the form for editing the specified permission.
     */
    public function editPermission(Permission $permission)
    {
        return view('admin.roles.edit-permission', compact('permission'));
    }

    /**
     * Update the specified permission in storage.
     */
    public function updatePermission(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name,'.$permission->id],
            'guard_name' => ['sometimes', 'string', 'max:255'],
        ]);

        $permission->update([
            'name' => $request->name,
            'guard_name' => $request->guard_name ?? 'web',
        ]);

        return redirect()->route('admin.roles.permissions')
            ->with('success', 'Permission updated successfully.');
    }

    /**
     * Remove the specified permission from storage.
     */
    public function destroyPermission(Permission $permission)
    {
        $permissionName = $permission->name;
        $permissionId = $permission->id;

        $permission->delete();

        return redirect()->route('admin.roles.permissions')
            ->with('success', 'Permission deleted successfully.');
    }
}
