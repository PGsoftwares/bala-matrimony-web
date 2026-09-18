<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function getNotificationLists(Request $request): JsonResponse
    {
        $user_id = $request->input('user_id');

        // Fetch notifications for the user
        $notifications = DB::table('notifications')
            ->where('notifiable_id', $user_id)
            ->where('notifiable_type', 'App\\Models\\User') // Assuming the notifiable_type is 'User'
            ->orderBy('created_at', 'desc')
            ->get();

        // Transform notifications
        $transformedNotifications = $notifications->map(function ($notification) {
            $data = json_decode($notification->data, true);
            return [
                'id' => $notification->id,
                'title' => $data['title'] ?? 'No title',
                'message' => $data['message'] ?? 'No message',
                'datetime' => Carbon::parse($notification->created_at)->format('d-m-Y H:i:s'),
                'status' => $notification->read_at ? 'read' : 'unread',
            ];
        });

        // Count unread notifications
        $unreadCount = $notifications->whereNull('read_at')->count();

        return response()->json([
            'notifications' => $transformedNotifications,
            'unread_count' => $unreadCount,
            'status' => 'success',
        ]);
    }

    public function markNotificationAsRead(Request $request): JsonResponse
    {
        $notificationId = $request->input('notification_id');

        // Find the notification by ID
        $notification = DB::table('notifications')->where('id', $notificationId)->first();

        if (!$notification) {
            return response()->json([
                'status' => 'error',
                'message' => 'Notification not found.',
            ], 404);
        }

        // Mark the notification as read by updating the read_at field
        DB::table('notifications')
            ->where('id', $notificationId)
            ->update(['read_at' => now()]);

        return response()->json([
            'status' => 'success',
            'message' => 'Notification marked as read.',
        ]);
    }

    public function getSeasonalNotifications(Request $request): JsonResponse
    {
        $notifications = DB::table('seasonal_notification')
            ->orderByDesc('id')
            ->get();

        $transformedNotifications = $notifications->map(function ($notification) {
            return [
                'id' => $notification->id,
                'title' => $notification->title ?? 'No title',
                'message' => $notification->message ?? 'No message',
                'datetime' => !empty($notification->sent_at) ? Carbon::parse($notification->sent_at)->format('d-m-Y H:i:s') : 'N/A',
                'is_read' => (bool) $notification->is_read,
            ];
        });

        return response()->json([
            'status' => 'success',
            'count' => $notifications->count(),
            'notifications' => $transformedNotifications,
        ]);
    }

    public function markSeasonalNotificationAsRead(Request $request): JsonResponse
    {
        $id = $request->input('id') ?? $request->input('notification_id');

        $updated = DB::table('seasonal_notification')
            ->where('id', $id)
            ->update(['is_read' => 1]);

        if (!$updated) {
            return response()->json([
                'status' => 'error',
                'message' => 'Notification not found or already marked as read.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Seasonal notification marked as read.',
        ]);
    }

    public function deleteSeasonalNotification(Request $request): JsonResponse
    {
        $id = $request->input('id') ?? $request->input('notification_id');

        $deleted = DB::table('seasonal_notification')
            ->where('id', $id)
            ->delete();

        if (!$deleted) {
            return response()->json([
                'status' => 'error',
                'message' => 'Notification not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Seasonal notification deleted.',
        ]);
    }
}
