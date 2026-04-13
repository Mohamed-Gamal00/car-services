<?php

namespace App\Console\Commands;

use App\Helper\Helper;
use App\Jobs\MakeCaptainAvailableJob;
use App\Models\Captain;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessUnassignedOrders extends Command
{
    use Helper;

    protected $signature = 'orders:process-unassigned';
    protected $description = 'Process unassigned orders and assign available captains';

    public function handle()
    {
        $unassignedOrders = Order::whereNull('captain_id')
            ->where('booking_date', now()->toDateString())
            ->where('payment_status', 'paid')
            ->get();

        $this->info("Found {$unassignedOrders->count()} unassigned orders for today");

        foreach ($unassignedOrders as $order) {
            $captain = Captain::available()->first();
            
            if ($captain) {
                // Assign captain to the order
                $order->update([
                    'captain_id' => $captain->id,
                    'order_status_id' => 3, // Assigned status
                ]);

                // Mark captain as busy
                $captain->update(['status' => 'busy']);

                // Send notification to captain
                $this->sendCaptainNotification($captain, $order);

                // Schedule captain to be available after service duration
                $duration = $order->getServiceDuration();
                MakeCaptainAvailableJob::dispatch($captain->id)
                    ->delay(now()->addMinutes($duration));

                $this->info("Assigned Captain ID {$captain->id} to Order ID {$order->id}");
            } else {
                $this->warn("No available captain for Order ID {$order->id}");
            }
        }

        $this->info('Finished processing unassigned orders');
    }

    private function sendCaptainNotification($captain, $order)
    {
        app()->setLocale($captain->preferred_language ?? 'ar');
        $tokens = $captain->deviceTokens->pluck('token')->toArray();

        if ($tokens) {
            $data = ['order_id' => $order->id];
            $this->notifyByFirebase(
                __('general.new_notification'),
                __('general.There_is_a_new_request_for_you'),
                $tokens,
                $data
            );
            Log::info('Notification Sent to Firebase', ['tokens' => $tokens, 'data' => $data]);
        } else {
            Log::error('No device tokens for captain', ['captain_id' => $captain->id]);
        }
    }
}