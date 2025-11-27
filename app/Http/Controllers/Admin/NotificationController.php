<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Notification;

class NotificationController extends Controller
{
    /**
     * Display a listing of notifications.
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $type = $request->input('type');
        $status = $request->input('status'); // read, unread, all
        $user = $request->input('user_id');

        // Build query
        $query = DatabaseNotification::with('notifiable')->latest();

        if ($type) {
            $query->where('type', 'like', "%{$type}%");
        }

        if ($status === 'read') {
            $query->whereNotNull('read_at');
        } elseif ($status === 'unread') {
            $query->whereNull('read_at');
        }

        if ($user) {
            $query->where('notifiable_id', $user)
                ->where('notifiable_type', User::class);
        }

        // Get paginated results
        $notifications = $query->paginate(20);

        // Get all users for filter
        $users = User::all();

        // Get notification types
        $types = DatabaseNotification::select('type')
            ->distinct()
            ->pluck('type')
            ->map(function ($type) {
                return class_basename($type);
            })
            ->unique()
            ->values();

        // Log the view action

        return view('admin.notifications.index', compact('notifications', 'users', 'types'));
    }

    /**
     * Display the specified notification.
     */
    public function show(DatabaseNotification $notification)
    {
        // Log the view action

        return view('admin.notifications.show', compact('notification'));
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead(DatabaseNotification $notification)
    {
        $notification->markAsRead();

        // Log the action

        return response()->json([
            'success' => true,
            'message' => 'Notificação marcada como lida.',
        ]);
    }

    /**
     * Mark notification as unread.
     */
    public function markAsUnread(DatabaseNotification $notification)
    {
        $notification->markAsUnread();

        // Log the action

        return response()->json([
            'success' => true,
            'message' => 'Notificação marcada como não lida.',
        ]);
    }

    /**
     * Delete notification.
     */
    public function destroy(DatabaseNotification $notification)
    {
        $notification->delete();

        // Log the action

        return response()->json([
            'success' => true,
            'message' => 'Notificação excluída com sucesso.',
        ]);
    }

    /**
     * Send notification to users.
     */
    public function create()
    {
        $users = User::all();

        return view('admin.notifications.create', compact('users'));
    }

    /**
     * Store and send notification.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'users' => ['required', 'array'],
            'users.*' => ['exists:users,id'],
            'type' => ['required', 'in:info,warning,success,error'],
        ]);

        $users = User::whereIn('id', $request->users)->get();

        foreach ($users as $user) {
            $user->notify(new \App\Notifications\GeneralNotification(
                $request->title,
                $request->message,
                $request->type,
                route('admin.notifications.index')
            ));
        }

        // Log the action

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notificações enviadas com sucesso.');
    }

    /**
     * Mark all notifications as read for a user.
     */
    public function markAllAsRead(Request $request)
    {
        $userId = $request->input('user_id');

        if ($userId) {
            $user = User::findOrFail($userId);
            $user->unreadNotifications->markAsRead();
        } else {
            // Mark all notifications as read for all users
            DatabaseNotification::whereNull('read_at')->update(['read_at' => now()]);
        }

        // Log the action

        return response()->json([
            'success' => true,
            'message' => 'Todas as notificações foram marcadas como lidas.',
        ]);
    }

    /**
     * Get notification statistics.
     */
    public function statistics()
    {
        $stats = [
            'total' => DatabaseNotification::count(),
            'unread' => DatabaseNotification::whereNull('read_at')->count(),
            'read' => DatabaseNotification::whereNotNull('read_at')->count(),
            'today' => DatabaseNotification::whereDate('created_at', today())->count(),
            'this_week' => DatabaseNotification::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => DatabaseNotification::whereMonth('created_at', now()->month)->count(),
        ];

        // Log the view action

        return view('admin.notifications.statistics', compact('stats'));
    }
}
