<?php

namespace App\Http\Services\Checkout;

use App\Models\Admin;
use App\Models\Order;
use App\Notifications\OrderCreatedEmailAdmin;
use App\Notifications\OrderCreatedNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

/**
 * Service for sending notifications to admins
 */
class AdminNotificationService
{
    /**
     * Send notifications to admins about new order
     */
    public function notifyNewOrder(Order $order): void
    {
        try {
            // Load relationships for email template
            $order->load(['user', 'car', 'service', 'userPackage.package', 'choices']);
            
            $admins = Admin::where('is_super_admin', 1)->get();

            if ($admins->isEmpty()) {
                Log::warning('No super admins found to notify');
                return;
            }

            // Send database notification
            $this->sendDatabaseNotification($admins, $order);

            // Send email notifications
            $this->sendEmailNotifications($admins, $order);

            Log::info('Admin notifications sent', ['order_id' => $order->id]);
        } catch (\Exception $e) {
            Log::error('Failed to send admin notifications', [
                'order_id' => $order->id ?? 'unknown',
                'error' => $e->getMessage()
            ]);
            // Don't throw - allow checkout to continue
        }
    }

    /**
     * Send database notification
     */
    protected function sendDatabaseNotification($admins, Order $order): void
    {
        try {
            Notification::send($admins, new OrderCreatedNotification($order));
            Log::info('Database notification sent to admins', ['order_id' => $order->id]);
        } catch (\Exception $e) {
            Log::warning('Failed to send database notification', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send email notifications
     */
    protected function sendEmailNotifications($admins, Order $order): void
    {
        $validAdmins = $admins->filter(function ($admin) {
            return filter_var($admin->email, FILTER_VALIDATE_EMAIL);
        });

        foreach ($validAdmins as $admin) {
            try {
                Notification::route('mail', $admin->email)
                    ->notify(new OrderCreatedEmailAdmin($order));
                    
                Log::info('Email sent to admin', [
                    'admin_email' => $admin->email,
                    'order_id' => $order->id
                ]);
            } catch (\Exception $e) {
                Log::warning('Failed to send email to admin', [
                    'admin_email' => $admin->email,
                    'order_id' => $order->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
}
