<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use OwenIt\Auditing\Models\Audit;

class AuditService
{
    /**
     * Log an action in the system using Laravel Audit.
     * This method creates a custom audit entry for system actions.
     *
     * @return \OwenIt\Auditing\Models\Audit
     */
    public static function log(string $module, string $action, string $description, ?array $data = null)
    {
        $userId = Auth::id();

        // Create a custom audit entry for system actions
        $audit = new Audit;
        $audit->user_id = $userId;
        $audit->event = 'system_action';
        $audit->auditable_type = 'system';
        $audit->auditable_id = 0;
        $audit->old_values = [];
        $audit->new_values = [
            'module' => $module,
            'action' => $action,
            'description' => $description,
            'data' => $data,
        ];
        $audit->url = Request::url();
        $audit->ip_address = Request::ip();
        $audit->user_agent = Request::userAgent();
        $audit->tags = json_encode(['module' => $module, 'action' => $action]);
        $audit->save();

        return $audit;
    }

    /**
     * Get logs for a specific module.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getLogsForModule(string $module)
    {
        return Audit::whereJsonContains('tags', ['module' => $module])
            ->with('user')
            ->latest()
            ->get();
    }

    /**
     * Get logs for a specific user.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getLogsForUser(int $userId)
    {
        return Audit::where('user_id', $userId)
            ->with('user')
            ->latest()
            ->get();
    }

    /**
     * Get logs for a specific action.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getLogsForAction(string $action)
    {
        return Audit::whereJsonContains('tags', ['action' => $action])
            ->with('user')
            ->latest()
            ->get();
    }

    /**
     * Get logs for a specific date range.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getLogsForDateRange(string $startDate, string $endDate)
    {
        return Audit::whereBetween('created_at', [$startDate, $endDate])
            ->with('user')
            ->latest()
            ->get();
    }

    /**
     * Get system action logs (custom audit entries).
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getSystemLogs()
    {
        return Audit::where('auditable_type', 'system')
            ->with('user')
            ->latest()
            ->get();
    }

    /**
     * Get model audit logs (standard Laravel Audit entries).
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getModelLogs()
    {
        return Audit::where('auditable_type', '!=', 'system')
            ->with('user')
            ->latest()
            ->get();
    }
}
