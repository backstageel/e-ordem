<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\CreateUserAction;
use App\Actions\Admin\DeleteUserAction;
use App\Actions\Admin\UpdateUserAction;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $search = $request->input('search');
        $role = $request->input('role');

        // Build query
        $query = User::with('roles')->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($role) {
            $query->whereHas('roles', function ($q) use ($role) {
                $q->where('name', $role);
            });
        }

        // Get paginated results
        $users = $query->paginate(20);

        // Get all roles for filter
        $roles = Role::all();

        // Log the view action

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = Role::all();

        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        try {
            $action = new CreateUserAction;
            $user = $action->execute($request->all());

            return redirect()->route('admin.users.index')
                ->with('success', __('User created successfully.'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('An error occurred while creating the user: :message', ['message' => $e->getMessage()]))
                ->withInput();
        }
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load('roles', 'person.gender', 'person.nationality');

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $user->load('roles');
        $roles = Role::all();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        try {
            $action = new UpdateUserAction;
            $action->execute($user, $request->all());

            return redirect()->route('admin.users.index')
                ->with('success', __('User updated successfully.'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('An error occurred while updating the user: :message', ['message' => $e->getMessage()]))
                ->withInput();
        }
    }

    /**
     * Show the form for changing the user's password.
     */
    public function changePassword(User $user)
    {
        return view('admin.users.change-password', compact('user'));
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request, User $user)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', __('Password updated successfully.'));
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        try {
            $action = new DeleteUserAction;
            $action->execute($user);

            return redirect()->route('admin.users.index')
                ->with('success', __('User deleted successfully.'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('An error occurred while deleting the user: :message', ['message' => $e->getMessage()]));
        }
    }

    /**
     * Display a listing of roles.
     */
    public function roles()
    {
        $roles = Role::withCount('users')->get();

        // Log the view action

        return view('admin.users.roles', compact('roles'));
    }

    /**
     * Show the form for creating a new role.
     */
    public function createRole()
    {
        return view('admin.users.create-role');
    }

    /**
     * Store a newly created role in storage.
     */
    public function storeRole(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
        ]);

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web',
        ]);

        return redirect()->route('admin.users.roles')
            ->with('success', __('Role created successfully.'));
    }

    /**
     * Show the form for editing the specified role.
     */
    public function editRole(Role $role)
    {
        return view('admin.users.edit-role', compact('role'));
    }

    /**
     * Update the specified role in storage.
     */
    public function updateRole(Request $request, Role $role)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name,'.$role->id],
        ]);

        $role->update([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.users.roles')
            ->with('success', __('Role updated successfully.'));
    }

    /**
     * Remove the specified role from storage.
     */
    public function destroyRole(Role $role)
    {
        $roleName = $role->name;
        $roleId = $role->id;

        $role->delete();

        return redirect()->route('admin.users.roles')
            ->with('success', __('Role deleted successfully.'));
    }
}
