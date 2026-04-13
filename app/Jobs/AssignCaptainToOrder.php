<?php

namespace App\Jobs;

use App\Helper\Helper;
use App\Models\Captain;
use App\Models\Order;
use App\Models\UserPackage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AssignCaptainToOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Helper;

    public function __construct()
    {
    }

    public function handle()
    {
        $today = now()->toDateString();
        $unassignedOrders = Order::whereNull('captain_id')
            ->where('payment_status', 'paid')
            ->where('booking_date', $today)
            ->get();

        foreach ($unassignedOrders as $order) {
            $captain = Captain::available()->first();
            
            if ($captain) {
                // Assign captain to order
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

                Log::info("Assigned Captain ID {$captain->id} to Order ID {$order->id}");
            }
        }
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
                    ->delay(now()->addMinutes($this->convertTimeToMinutes($duration)));

//                MakeCaptainAvailableJob::dispatch($captain->id)
//                    ->delay(now()->addMinutes($this->convertTimeToMinutes($order->products->first()->duration)));


            } else {
                break;
            }
        }

    }

    // Helper function to convert duration to minutes
    private function convertTimeToMinutes($duration)
    {
        list($hours, $minutes) = explode(':', $duration);
        return ($hours * 60) + $minutes;
    }
}
