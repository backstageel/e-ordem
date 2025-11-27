<?php

namespace App\Models;

use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole implements Auditable
{
    use AuditableTrait;

    /**
     * The attributes that should be audited.
     *
     * @var array
     */
    protected $auditInclude = [
        'name',
        'display_name',
        'guard_name',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'display_name',
        'guard_name',
    ];

    /**
     * The attributes that should not be audited.
     *
     * @var array
     */
    protected $auditExclude = [
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $auditHidden = [];

    /**
     * The attributes that should be visible in the audit.
     *
     * @var array
     */
    protected $auditVisible = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $auditStrict = false;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $auditTimestamps = true;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'restored',
    ];

    /**
     * Get the audit data for the model.
     */
    public function getAuditData(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'display_name' => $this->display_name,
            'guard_name' => $this->guard_name,
        ];
    }

    /**
     * Get the audit user for the model.
     *
     * @return mixed
     */
    public function getAuditUser()
    {
        return auth()->user();
    }

    /**
     * Get the audit URL for the model.
     */
    public function getAuditUrl(): ?string
    {
        return route('admin.roles.show', $this->id);
    }

    /**
     * Get the audit tags for the model.
     */
    public function getAuditTags(): array
    {
        return ['role', 'permission'];
    }

    /**
     * Get the audit metadata for the model.
     */
    public function getAuditMetadata(): array
    {
        return [
            'permissions_count' => $this->permissions()->count(),
            'users_count' => $this->users()->count(),
        ];
    }
}
