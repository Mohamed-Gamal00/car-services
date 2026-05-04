<?php

namespace App\Http\Services\Payment;

use App\Jobs\AssignCaptainToOrder;
use App\Models\Captain;
use App\Models\Order;
use App\Notifications\CaptainAssignedNotification;
use Illuminate\Support\Facades\Log;

/**
 * Service for handling captain assignment after payment
 */
class CaptainAssignmentService
{
    /**
     * Get available captain
     */
    public function getAvailableCaptain(): ?Captain
    {
        return Captain::where('status', 'available')
            ->where('is_active', 1)
            ->first();
    }

    /**
     * Assign captain to order if booking is today
     */
    public function assignCaptainIfToday(Order $order): void
    {
        // Only assign captain if booking is today
        if ($order->booking_date !== now()->toDateString()) {
            Log::info('Booking is not today, skipping captain assignment', [
                'order_id' => $order->id,
                'booking_date' => $order->booking_date
            ]);
            return;
        }

        $captain = $this->getAvailableCaptain();

        if (!$captain) {
            Log::warning('No available captain found', [
                'order_id' => $order->id
            ]);
            return;
        }

        // Notify captain
        $captain->notify(new CaptainAssignedNotification($order));

        // Dispatch job to assign captain
        dispatch(new AssignCaptainToOrder());

        Log::info('Captain assigned to order', [
            'order_id' => $order->id,
            'captain_id' => $captain->id
        ]);
    }
}
