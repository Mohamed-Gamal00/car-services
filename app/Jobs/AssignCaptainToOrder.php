<?php

namespace App\Jobs;

use App\Helper\Helper;
use App\Models\Captain;
use App\Models\Order;
use App\Models\UserPackage;
use App\Notifications\CaptainAssignedNotification;
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
            ->where('booking_date', $today) // Only today's orders
            ->get();
        foreach ($unassignedOrders as $order) {
            $captain = Captain::where('status', 'available')->where('is_active', 1)->first();
            if ($captain) {
                $order->captain_id = $captain->id;
                $order->save();

                $captain->status = 'busy';
                $captain->save();
                $captain = Captain::findOrFail($order->captain_id);
                app()->setLocale($captain->lang ?? 'ar');
                $tokens = $captain->devicetokens->pluck('token')->toArray();
                Log::info('Captain Device Tokens', ['captain_id' => $captain->id]);

                if ($tokens) {
                    $data = ['order_id' => $order->id];
                    $this->notifyByFirebase(__('general.new_notification'), __('general.There_is_a_new_request_for_you'), $tokens, $data);
                    Log::info('Notification Sent to Firebase', ['tokens' => $tokens, 'data' => $data]);

                } else {
                    Log::error('No device tokens for captain', ['captain_id' => $captain->id]);
                }

                $userpackage = UserPackage::with('package')->find($order->user_package_id);
                $product = $order->products->first();

                $duration = $product?->duration ?? $userpackage?->package?->duration;

                MakeCaptainAvailableJob::dispatch($captain->id)
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
