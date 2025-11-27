<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Models\Audit;

class AuditController extends Controller
{
    /**
     * Display a listing of audit logs.
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $event = $request->input('event');
        $auditableType = $request->input('auditable_type');
        $userId = $request->input('user_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Build query using Laravel Audit
        $query = Audit::latest();

        if ($event) {
            $query->where('event', $event);
        }

        if ($auditableType) {
            $query->where('auditable_type', $auditableType);
        }

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->where('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }

        // Get paginated results
        $logs = $query->paginate(20);

        // Get unique events and auditable types for filters
        $events = Audit::select('event')->distinct()->pluck('event');
        $auditableTypes = Audit::select('auditable_type')->distinct()->pluck('auditable_type');
        $users = User::whereIn('id', Audit::select('user_id')->distinct()->pluck('user_id'))->get();


        return view('admin.audit.index', compact('logs', 'events', 'auditableTypes', 'users'));
    }

    /**
     * Display the specified audit log.
     */
    public function show(Audit $log)
    {

        return view('admin.audit.show', compact('log'));
    }

    /**
     * Display audit statistics.
     */
    public function statistics()
    {
        // Get counts by auditable type
        $auditableTypeStats = Audit::select('auditable_type', DB::raw('count(*) as count'))
            ->groupBy('auditable_type')
            ->orderBy('count', 'desc')
            ->get();

        // Get counts by event
        $eventStats = Audit::select('event', DB::raw('count(*) as count'))
            ->groupBy('event')
            ->orderBy('count', 'desc')
            ->get();

        // Get counts by user
        $userStats = Audit::select('user_id', DB::raw('count(*) as count'))
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->orderBy('count', 'desc')
            ->get()
            ->map(function ($stat) {
                $user = \App\Models\User::find($stat->user_id);
                $stat->user_name = $user ? $user->name : "Utilizador #{$stat->user_id} (não encontrado)";
                return $stat;
            });

        // Get counts by date (last 30 days)
        $dateStats = Audit::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();


        return view('admin.audit.statistics', compact('auditableTypeStats', 'eventStats', 'userStats', 'dateStats'));
    }

    /**
     * Export audit logs.
     */
    public function export(Request $request)
    {
        // Get filter parameters
        $event = $request->input('event');
        $auditableType = $request->input('auditable_type');
        $userId = $request->input('user_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Build query using Laravel Audit
        $query = Audit::latest();

        if ($event) {
            $query->where('event', $event);
        }

        if ($auditableType) {
            $query->where('auditable_type', $auditableType);
        }

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->where('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }

        // Get all logs
        $logs = $query->get();


        // Generate CSV file
        $filename = 'audit_export_'.now()->format('Y-m-d_H-i-s').'.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($logs) {
            $file = fopen('php://output', 'w');

            // Add headers
            fputcsv($file, ['ID', 'User', 'Event', 'Auditable Type', 'Auditable ID', 'Old Values', 'New Values', 'IP Address', 'User Agent', 'Date/Time']);

            // Add data
            foreach ($logs as $log) {
                $userName = 'System';
                if ($log->user_type && $log->user_id) {
                    $user = \App\Models\User::find($log->user_id);
                    if ($user) {
                        $userName = $user->name;
                    } else {
                        $userName = "Utilizador #{$log->user_id} (não encontrado)";
                    }
                }
                
                fputcsv($file, [
                    $log->id,
                    $userName,
                    $log->event,
                    $log->auditable_type,
                    $log->auditable_id,
                    json_encode($log->old_values),
                    json_encode($log->new_values),
                    $log->ip_address,
                    $log->user_agent,
                    $log->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
