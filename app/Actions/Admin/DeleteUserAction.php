<?php

namespace App\Actions\Admin;

use App\Models\User;

class DeleteUserAction
{
    /**
     * Delete a user.
     */
    public function execute(User $user): bool
    {
        $userName = $user->name;
        $userId = $user->id;

        // Check if user can be deleted
        $this->validateDeletion($user);

        // Delete the user
        $deleted = $user->delete();

        return $deleted;
    }

    /**
     * Validate if the user can be deleted.
     */
    private function validateDeletion(User $user): void
    {
        // Prevent deletion of the current user
        if ($user->id === auth()->id()) {
            throw new \Exception('You cannot delete your own account.');
        }

        // Prevent deletion of super-admin users (optional business rule)
        if ($user->hasRole('super-admin')) {
            throw new \Exception('Super admin users cannot be deleted.');
        }

        // Add other business rules as needed
        // For example, prevent deletion if user has active registrations, etc.
    }
}
