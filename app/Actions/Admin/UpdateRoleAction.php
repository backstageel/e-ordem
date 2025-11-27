<?php

namespace App\Actions\Admin;

use Spatie\Permission\Models\Role;

class UpdateRoleAction
{
    /**
     * Update a role.
     */
    public function execute(Role $role, array $data): Role
    {
        // Validate the data
        $validatedData = $this->validateData($data, $role);

        // Update the role
        $role->update([
            'name' => $validatedData['name'],
            'guard_name' => $validatedData['guard_name'] ?? 'web',
        ]);

        // Update permissions if provided
        if (isset($validatedData['permissions']) && is_array($validatedData['permissions'])) {
            $role->syncPermissions($validatedData['permissions']);
        }

        return $role->fresh();
    }

    /**
     * Validate the input data.
     */
    private function validateData(array $data, Role $role): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255', 'unique:roles,name,'.$role->id],
            'guard_name' => ['sometimes', 'string', 'max:255'],
            'permissions' => ['sometimes', 'array'],
        ];

        $validator = validator($data, $rules);

        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        return $validator->validated();
    }
}
