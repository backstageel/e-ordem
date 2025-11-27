<?php

namespace App\Actions\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class CreateUserAction
{
    /**
     * Create a new user.
     */
    public function execute(array $data): User
    {
        // Validate the data
        $validatedData = $this->validateData($data);

        // Create the user
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        // Assign roles if provided
        if (isset($validatedData['roles']) && is_array($validatedData['roles'])) {
            $user->syncRoles($validatedData['roles']);
        }

        return $user;
    }

    /**
     * Validate the input data.
     */
    private function validateData(array $data): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'roles' => ['sometimes', 'array'],
        ];

        $validator = validator($data, $rules);

        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        return $validator->validated();
    }
}
