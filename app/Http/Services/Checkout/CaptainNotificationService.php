<?php

namespace App\Http\Services\Checkout;

use App\Helper\Helper;
use App\Jobs\MakeCaptainAvailableJob;
use App\Models\Captain;
use App\Models\Order;
use App\Models\Package;
use App\Notifications\CaptainAssignedNotification;
use Illuminate\Support\Facades\Log;

/**
 * Service for handling captain notifications
 */
class CaptainNotificationService
{
    use Helper;

    /**
     * Notify captain about order assignment
     */
    public function notifyCaptainAssignment(Captain $captain, Order $order, Package $package): void
    {
        try {
            // Send in-app notification
            $captain->notify(new CaptainAssignedNotification($order));

            // Update captain status
            $captain->update(['status' => 'busy']);

            // Send Firebase notification
            $this->sendFirebaseNotification($captain, $order);

            // Schedule captain availability
            $this->scheduleCaptainAvailability($captain, $package);

            Log::info('Captain notified successfully', [
                'captain_id' => $captain->id,
                'order_id' => $order->id
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to notify captain', [
                'captain_id' => $captain->id,
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send Firebase notification to captain
     */
    protected function sendFirebaseNotification(Captain $captain, Order $order): void
    {
        $captain->refresh();
        app()->setLocale($captain->lang ?? 'ar');
        
        $tokens = $captain->devicetokens->pluck('token')->toArray();

        if (empty($tokens)) {
            Log::warning('No device tokens for captain', ['captain_id' => $captain->id]);
            return;
        }

        $data = ['order_id' => $order->id];
        $this->notifyByFirebase(
            __('general.new_notification'),
            __('general.There_is_a_new_request_for_you'),
            $tokens,
            $data
        );

        Log::info('Firebase notification sent', [
            'captain_id' => $captain->id,
            'tokens_count' => count($tokens)
        ]);
    }

    /**
     * Schedule captain to become available after service duration
     */
    protected function scheduleCaptainAvailability(Captain $captain, Package $package): void
    {
        $durationMinutes = $this->convertTimeToMinutes($package->duration);
        
        MakeCaptainAvailableJob::dispatch($captain->id)
            ->delay(now()->addMinutes($durationMinutes));

        Log::info('Captain availability scheduled', [
            'captain_id' => $captain->id,
            'duration_minutes' => $durationMinutes
        ]);
    }

    /**
     * Convert time string to minutes
     */
    protected function convertTimeToMinutes(string $duration): int
    {
        list($hours, $minutes) = explode(':', $duration);
        return ($hours * 60) + $minutes;
    }
}
