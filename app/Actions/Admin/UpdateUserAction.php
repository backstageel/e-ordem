<?php

namespace App\Actions\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdateUserAction
{
    /**
     * Update a user.
     */
    public function execute(User $user, array $data): User
    {
        // Validate the data
        $validatedData = $this->validateData($data, $user);

        // Update the user
        $updateData = [
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
        ];

        // Update password if provided
        if (isset($validatedData['password']) && ! empty($validatedData['password'])) {
            $updateData['password'] = Hash::make($validatedData['password']);
        }

        $user->update($updateData);

        // Update roles if provided
        if (isset($validatedData['roles']) && is_array($validatedData['roles'])) {
            $user->syncRoles($validatedData['roles']);
        }

        return $user->fresh();
    }

    /**
     * Validate the input data.
     */
    private function validateData(array $data, User $user): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['sometimes', 'nullable', 'string', 'min:8'],
            'roles' => ['sometimes', 'array'],
        ];

        $validator = validator($data, $rules);

        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        return $validator->validated();
    }
}
