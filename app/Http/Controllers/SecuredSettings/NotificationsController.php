<?php

namespace App\Http\Controllers\SecuredSettings;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\FCMController;
use App\Models\User;
use App\Notifications\SeasonalNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class NotificationsController extends Controller
{
    protected FCMController $fcmController;

    public function __construct(FCMController $fcmController)
    {
        $this->fcmController = $fcmController;
    }

    public function seasonalNotification(): View
    {
        $notifications = DB::table('seasonal_notification')
            ->orderByDesc('id')
            ->get();

        return view('admin.securedSettings.seasonal_notification', compact('notifications'));
    }

    public function seasonalNotificationStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'message' => 'required|string',
        ]);

        try {
            DB::table('seasonal_notification')->insert([
                'title' => $validated['title'],
                'message' => $validated['message'],
                'sent_at' => now(),
            ]);

            // 1. Dispatch database notification to all users so it appears in notification feeds & API
            $users = User::all();
            if ($users->isNotEmpty()) {
                Notification::send($users, new SeasonalNotification([
                    'title' => $validated['title'],
                    'message' => $validated['message'],
                ]));
            }

            // 2. Send Mobile Push Notification via FCM to all users with active device_tokens
            $this->fcmController->sendFcmNotificationToAll(
                $validated['title'],
                $validated['message'],
                [
                    'type' => 'seasonal_notification',
                    'title' => $validated['title'],
                    'message' => $validated['message'],
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                ]
            );

            return redirect('admin/seasonal_notification')
                ->with('success', 'Seasonal Push Notification sent successfully.');
        } catch (\Throwable $e) {
            return redirect('admin/seasonal_notification')
                ->with('error', 'Failed to send notification: ' . $e->getMessage());
        }
    }

    public function FetchNotifications(Request $request): JsonResponse
    {
        $notifications = DB::table('seasonal_notification')
            ->orderByDesc('sent_at')
            ->get();

        return response()->json([
            'count' => $notifications->count(),
            'notifications' => $notifications
        ]);
    }


    public function ReadNotification(Request $request): JsonResponse
    {
        DB::table('seasonal_notification')
            ->where('id', $request->id)
            ->update(['is_read' => 1]);

        return response()->json(['status' => 'success']);
    }

    public function DeleteNotification(Request $request): JsonResponse
    {
        DB::table('seasonal_notification')
            ->where('id', $request->id)
            ->delete();

        return response()->json(['status' => 'deleted']);
    }
}
