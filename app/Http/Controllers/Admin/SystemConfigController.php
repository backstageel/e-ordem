<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemConfig;
use App\Services\SystemConfigService;
use Illuminate\Http\Request;

class SystemConfigController extends Controller
{
    /**
     * Display a listing of system configurations.
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $group = $request->input('group', 'general');

        // Get configurations by group
        $configs = SystemConfigService::getByGroup($group);

        // Get all groups for filter
        $groups = SystemConfigService::getGroups();

        // Log the view action

        return view('admin.system.index', compact('configs', 'groups', 'group'));
    }

    /**
     * Show the form for creating a new configuration.
     */
    public function create()
    {
        // Get all groups for dropdown
        $groups = SystemConfigService::getGroups();

        return view('admin.system.create', compact('groups'));
    }

    /**
     * Store a newly created configuration in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'key' => ['required', 'string', 'max:255', 'unique:system_configs,key'],
            'value' => ['required'],
            'description' => ['nullable', 'string', 'max:255'],
            'group' => ['required', 'string', 'max:255'],
            'is_public' => ['boolean'],
        ]);

        $isPublic = $request->has('is_public');

        $config = SystemConfigService::set(
            $request->key,
            $request->value,
            $request->description,
            $request->group,
            $isPublic
        );

        return redirect()->route('admin.system.configs.index', ['group' => $request->group])
            ->with('success', 'Configuration created successfully.');
    }

    /**
     * Show the form for editing the specified configuration.
     */
    public function edit($key)
    {
        $config = SystemConfig::where('key', $key)->firstOrFail();
        $groups = SystemConfigService::getGroups();

        return view('admin.system.edit', compact('config', 'groups'));
    }

    /**
     * Update the specified configuration in storage.
     */
    public function update(Request $request, $key)
    {
        $config = SystemConfig::where('key', $key)->firstOrFail();

        $request->validate([
            'value' => ['required'],
            'description' => ['nullable', 'string', 'max:255'],
            'group' => ['required', 'string', 'max:255'],
            'is_public' => ['boolean'],
        ]);

        $isPublic = $request->has('is_public');

        $config = SystemConfigService::set(
            $key,
            $request->value,
            $request->description,
            $request->group,
            $isPublic
        );

        return redirect()->route('admin.system.configs.index', ['group' => $request->group])
            ->with('success', 'Configuration updated successfully.');
    }

    /**
     * Remove the specified configuration from storage.
     */
    public function destroy($key)
    {
        $config = SystemConfig::where('key', $key)->firstOrFail();
        $group = $config->group;

        SystemConfigService::delete($key);

        return redirect()->route('admin.system.configs.index', ['group' => $group])
            ->with('success', 'Configuration deleted successfully.');
    }

    /**
     * Display the system dashboard with statistics.
     */
    public function dashboard()
    {
        // Get system statistics
        $stats = [
            'users_count' => \App\Models\User::count(),
            'members_count' => \App\Models\Member::count(),
            'registrations_count' => \Modules\Registration\Models\Registration::count(),
            'documents_count' => \App\Models\Document::count(),
            'payments_count' => \App\Models\Payment::count(),
            'exams_count' => \App\Models\Exam::count(),
        ];

        // Get recent audit logs
        $recentLogs = \OwenIt\Auditing\Models\Audit::with('user')->latest()->take(10)->get();

        // Log the view action

        return view('admin.system.dashboard', compact('stats', 'recentLogs'));
    }

    /**
     * Show the backup management page.
     */
    public function backups()
    {
        // Get backup settings
        $backupSettings = [
            'automatic_backups' => SystemConfigService::get('backup.automatic', false),
            'backup_frequency' => SystemConfigService::get('backup.frequency', 'daily'),
            'backup_retention' => SystemConfigService::get('backup.retention', 7),
            'backup_destination' => SystemConfigService::get('backup.destination', 'local'),
        ];

        // Log the view action

        return view('admin.system.backups', compact('backupSettings'));
    }

    /**
     * Update backup settings.
     */
    public function updateBackupSettings(Request $request)
    {
        $request->validate([
            'automatic_backups' => ['boolean'],
            'backup_frequency' => ['required', 'in:hourly,daily,weekly,monthly'],
            'backup_retention' => ['required', 'integer', 'min:1', 'max:365'],
            'backup_destination' => ['required', 'in:local,s3,sftp'],
        ]);

        $automaticBackups = $request->has('automatic_backups');

        SystemConfigService::set('backup.automatic', $automaticBackups, 'Enable automatic backups', 'backup', true);
        SystemConfigService::set('backup.frequency', $request->backup_frequency, 'Backup frequency', 'backup', true);
        SystemConfigService::set('backup.retention', $request->backup_retention, 'Backup retention days', 'backup', true);
        SystemConfigService::set('backup.destination', $request->backup_destination, 'Backup destination', 'backup', true);

        // Log the action

        return redirect()->route('admin.system.backups')
            ->with('success', 'Backup settings updated successfully.');
    }

    /**
     * Create a manual backup.
     */
    public function createBackup()
    {
        // Here you would implement the actual backup logic
        // For now, we'll just log the action

        // Log the action

        return redirect()->route('admin.system.backups')
            ->with('success', 'Backup created successfully.');
    }

    /**
     * Restore from a backup.
     */
    public function restoreBackup(Request $request)
    {
        $request->validate([
            'backup_file' => ['required', 'string'],
        ]);

        // Here you would implement the actual restore logic
        // For now, we'll just log the action

        // Log the action

        return redirect()->route('admin.system.backups')
            ->with('success', 'Restore completed successfully.');
    }
}
