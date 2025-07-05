<?php

namespace App\Http\Controllers\Api;

use App\Helper\ApiResponse;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\OrderStatus;
use App\Models\User;
use App\Notifications\CaptainNotifyArrival;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use Helper;

    public function captainNotification()
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

    public function captainShowNotification($id)
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


    public function captainDeleteNotification($id)
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
            'message' => __('general.Notifications_deleted'),
        ]);
    }

    public function captaindeleteAllNotifications()
    {
        $user = auth()->user();

        // Delete all notifications for the authenticated user
        $user->notifications()->delete();

        return response()->json([
            'success' => true,
            'message' => __('general.Notifications_deleted'),
        ]);
    }


    public function notifyCustomerArrival($id)
    {


        $captain = auth()->user();
        $order = $captain->orders()->find($id);
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => translateWithHTMLTags('this order not found')
            ], 404);
        }
        $client = User::findOrFail($order->user_id);
        app()->setLocale($client->lang ?? 'ar');

        $order->update([
            'order_status_id' => 3,
            'is_arrived' => 1,
        ]);
        $client = User::FindOrFail($order->user_id);
        $client->notify(new CaptainNotifyArrival($order));
        $deviceTokens = $client->devicetokens()->pluck('token');
        $title = __('general.captain_arrival_title');
        $body = __('general.captain_arrival_body');
        $tokens = $deviceTokens;
        $data = [
            'order_id' => 'testtt',
        ];

        $this->notifyByFirebase($title, $body, $tokens, $data);

        return response()->json([

            'message' => __('general.Notification_sent_successfully'),
            'is_arrivale' => true,
        ], 201);
    }

}
