<?php

namespace App\Actions\Admin;

use Spatie\Permission\Models\Role;

class DeleteRoleAction
{
    /**
     * Delete a role.
     */
    public function execute(Role $role): bool
    {
        $roleName = $role->name;
        $roleId = $role->id;

        // Check if role can be deleted
        $this->validateDeletion($role);

        // Delete the role
        $deleted = $role->delete();

        return $deleted;
    }

    /**
     * Validate if the role can be deleted.
     */
    private function validateDeletion(Role $role): void
    {
        // Prevent deletion of system roles
        $systemRoles = ['super-admin', 'admin'];

        if (in_array($role->name, $systemRoles)) {
            throw new \Exception('System roles cannot be deleted.');
        }

        // Check if role has users assigned
        if ($role->users()->count() > 0) {
            throw new \Exception('Cannot delete role that has users assigned. Please reassign users first.');
        }
    }
}
