<?php

namespace App\Actions\Admin;

use Spatie\Permission\Models\Role;

class CreateRoleAction
{
    /**
     * Create a new role.
     */
    public function execute(array $data): Role
    {
        // Validate the data
        $validatedData = $this->validateData($data);

        // Create the role
        $role = Role::create([
            'name' => $validatedData['name'],
            'guard_name' => $validatedData['guard_name'] ?? 'web',
        ]);

        // Assign permissions if provided
        if (isset($validatedData['permissions']) && is_array($validatedData['permissions'])) {
            $role->syncPermissions($validatedData['permissions']);
        }

        return $role;
    }

    /**
     * Validate the input data.
     */
    private function validateData(array $data): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
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
