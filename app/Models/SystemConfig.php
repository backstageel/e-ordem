<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class SystemConfig extends Model implements Auditable
{
    use AuditableTrait, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'key',
        'value',
        'description',
        'group',
        'is_public',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'value' => 'array',
    ];

    /**
     * The attributes that should be audited.
     *
     * @var array<string>
     */
    protected $auditInclude = [
        'key',
        'value',
        'description',
    ];

    /**
     * The attributes that should be excluded from auditing.
     *
     * @var array<string>
     */
    protected $auditExclude = [
        'created_at',
        'updated_at',
    ];

    /**
     * Get a configuration value by key.
     *
     * @param  mixed  $default
     * @return mixed
     */
    public static function getConfig(string $key, $default = null)
    {
        $config = static::where('key', $key)->first();

        return $config ? $config->value : $default;
    }

    /**
     * Set a configuration value.
     *
     * @param  mixed  $value
     * @return \App\Models\SystemConfig
     */
    public static function setConfig(string $key, $value, ?string $description = null)
    {
        $config = static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'description' => $description ?? $key,
            ]
        );

        return $config;
    }

    /**
     * Check if a configuration exists.
     */
    public static function hasConfig(string $key): bool
    {
        return static::where('key', $key)->exists();
    }

    /**
     * Delete a configuration.
     */
    public static function deleteConfig(string $key): bool
    {
        return static::where('key', $key)->delete() > 0;
    }
}
