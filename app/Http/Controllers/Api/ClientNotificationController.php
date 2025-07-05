<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\CaptainNotifyArrival;
use Illuminate\Http\Request;

class ClientNotificationController extends Controller
{
    public function clientNotification()
    {
        $user = auth()->user();
        // Retrieve all notifications
        $notifications = $user->notifications->map(function ($notification) {
            return [
                'id' => $notification->id,
                'type' => class_basename($notification->type), // Optional: Use to categorize by notification type
                'data' => $notification->data, // The data payload of the notification
                'read_at' => $notification->read_at, // Null if unread, timestamp if read
                'created_at' => $notification->created_at,
            ];
        });

        return response()->json([
            'success' => true,
            'notifications' => $notifications
        ]);
    }

    public function clientShowNotification($id)
    {
        $user = auth()->user();

        // Find a specific notification by ID
        $notification = $user->notifications()->find($id);

        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found'
            ], 404);
        }

        // Mark the notification as read when viewed
        if (!$notification->read_at) {
            $notification->markAsRead();
        }

        return response()->json([
            'success' => true,
            'notification' => [
                'id' => $notification->id,
                'type' => class_basename($notification->type),
                'data' => $notification->data,
                'read_at' => $notification->read_at,
                'created_at' => $notification->created_at,
            ]
        ]);
    }

    public function clientDeleteNotification($id)
    {
        $user = auth()->user();

        // Find the specific notification by ID
        $notification = $user->notifications()->find($id);

        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found',
            ], 404);
        }

        // Delete the notification
        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted successfully',
        ]);
    }

    public function clientdeleteAllNotifications()
    {
        $user = auth()->user();

        // Delete all notifications for the authenticated user
        $user->notifications()->delete();

        return response()->json([
            'success' => true,
            'message' => __('general.Notifications_deleted '),
        ]);
    }


}
