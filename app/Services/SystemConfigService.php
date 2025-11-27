<?php

namespace App\Services;

use App\Models\SystemConfig;

class SystemConfigService
{
    /**
     * Get a configuration value by key.
     *
     * @param  mixed  $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        return SystemConfig::getConfig($key, $default);
    }

    /**
     * Set a configuration value.
     *
     * @param  mixed  $value
     * @return \App\Models\SystemConfig
     */
    public static function set(string $key, $value, ?string $description = null, string $group = 'general', bool $isPublic = false)
    {
        $config = SystemConfig::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'description' => $description ?? $key,
                'group' => $group,
                'is_public' => $isPublic,
            ]
        );

        return $config;
    }

    /**
     * Check if a configuration exists.
     */
    public static function has(string $key): bool
    {
        return SystemConfig::hasConfig($key);
    }

    /**
     * Delete a configuration.
     */
    public static function delete(string $key): bool
    {
        $result = SystemConfig::deleteConfig($key);

        return $result;
    }

    /**
     * Get all configurations by group.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getByGroup(string $group = 'general')
    {
        return SystemConfig::where('group', $group)->get();
    }

    /**
     * Get all public configurations.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getPublic()
    {
        return SystemConfig::where('is_public', true)->get();
    }

    /**
     * Get all configuration groups.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getGroups()
    {
        $defaultGroups = collect([
            'general' => 'Geral',
            'email' => 'Email',
            'backup' => 'Backup',
            'security' => 'Segurança',
            'payment' => 'Pagamentos',
            'system' => 'Sistema',
            'notification' => 'Notificações',
            'audit' => 'Auditoria',
        ]);

        $existingGroups = SystemConfig::select('group')->distinct()->pluck('group');

        return $defaultGroups->merge($existingGroups)->unique();
    }
}
