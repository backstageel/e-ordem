<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;

class UserData extends Data
{
    public function __construct(
        #[Required, StringType, Max(255)]
        public string $name,

        #[Required, Email, Max(255), Unique('users', 'email')]
        public string $email,

        #[Required, StringType, Max(255)]
        public string $password,

        public ?array $roles = null,
        public ?int $id = null,
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {}

    /**
     * Create UserData from User model.
     */
    public static function fromUser(\App\Models\User $user): self
    {
        return new self(
            name: $user->name,
            email: $user->email,
            password: '', // Never include password in data transfer
            roles: $user->roles->pluck('name')->toArray(),
            id: $user->id,
            created_at: $user->created_at?->toISOString(),
            updated_at: $user->updated_at?->toISOString(),
        );
    }

    /**
     * Create UserData for update (without password validation).
     */
    public static function forUpdate(array $data, int $userId): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'] ?? '',
            roles: $data['roles'] ?? null,
            id: $userId,
        );
    }

    /**
     * Get validation rules for creation.
     */
    public static function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'roles' => ['sometimes', 'array'],
        ];
    }

    /**
     * Get validation rules for update.
     */
    public static function rulesForUpdate(int $userId): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$userId],
            'password' => ['sometimes', 'nullable', 'string', 'min:8'],
            'roles' => ['sometimes', 'array'],
        ];
    }

    /**
     * Get only the fillable attributes for User model.
     */
    public function toUserAttributes(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
        ];
    }

    /**
     * Get roles array.
     */
    public function getRoles(): array
    {
        return $this->roles ?? [];
    }
}
