<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;

class RoleData extends Data
{
    public function __construct(
        #[Required, StringType, Max(255), Unique('roles', 'name')]
        public string $name,

        public string $guard_name = 'web',
        public ?array $permissions = null,
        public ?int $id = null,
        public ?int $users_count = null,
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {}

    /**
     * Create RoleData from Role model.
     */
    public static function fromRole(\Spatie\Permission\Models\Role $role): self
    {
        return new self(
            name: $role->name,
            guard_name: $role->guard_name,
            permissions: $role->permissions->pluck('name')->toArray(),
            id: $role->id,
            users_count: $role->users_count ?? null,
            created_at: $role->created_at?->toISOString(),
            updated_at: $role->updated_at?->toISOString(),
        );
    }

    /**
     * Create RoleData for update (without unique validation).
     */
    public static function forUpdate(array $data, int $roleId): self
    {
        return new self(
            name: $data['name'],
            guard_name: $data['guard_name'] ?? 'web',
            permissions: $data['permissions'] ?? null,
            id: $roleId,
        );
    }

    /**
     * Get validation rules for creation.
     */
    public static function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'guard_name' => ['sometimes', 'string', 'max:255'],
            'permissions' => ['sometimes', 'array'],
        ];
    }

    /**
     * Get validation rules for update.
     */
    public static function rulesForUpdate(int $roleId): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:roles,name,'.$roleId],
            'guard_name' => ['sometimes', 'string', 'max:255'],
            'permissions' => ['sometimes', 'array'],
        ];
    }

    /**
     * Get only the fillable attributes for Role model.
     */
    public function toRoleAttributes(): array
    {
        return [
            'name' => $this->name,
            'guard_name' => $this->guard_name,
        ];
    }

    /**
     * Get permissions array.
     */
    public function getPermissions(): array
    {
        return $this->permissions ?? [];
    }

    /**
     * Get display name for the role.
     */
    public function getDisplayName(): string
    {
        return ucwords(str_replace(['-', '_'], ' ', $this->name));
    }

    /**
     * Get role description based on name.
     */
    public function getDescription(): string
    {
        return match ($this->name) {
            'super-admin' => 'Full system access with all permissions',
            'admin' => 'Administrative access to manage users and system settings',
            'member' => 'Regular member with access to personal features',
            'teacher' => 'Teacher with access to educational features',
            default => 'Custom role with specific permissions',
        };
    }
}
