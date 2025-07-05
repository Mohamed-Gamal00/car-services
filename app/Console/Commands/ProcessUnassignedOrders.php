<?php

namespace App\Console\Commands;

use App\Helper\Helper;
use App\Models\UserPackage;
use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Captain;
use App\Jobs\MakeCaptainAvailableJob;
use Illuminate\Support\Facades\Log;

class ProcessUnassignedOrders extends Command
{
    use Helper;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:process-unassigned';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign captains to unassigned orders for the current day';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get all unassigned orders for the current day
        $unassignedOrders = Order::whereNull('captain_id')
            ->where('booking_date', now()->toDateString())
            ->where('payment_status', 'paid')
            ->get();

        foreach ($unassignedOrders as $order) {
            $captain = Captain::where('status', 'available')->where('is_active', 1)->first();
            if ($captain) {
                // Assign captain to the order
                $order->captain_id = $captain->id;
                $order->save();

                // Mark captain as busy
                $captain->status = 'busy';
                $captain->save();
                $tokens = $captain->devicetokens->pluck('token')->toArray();
                Log::info('Captain Device Tokens', ['captain_id' => $captain->id, 'tokens' => $tokens]);

                if ($tokens) {
                    $data = ['order_id' => $order->id];
                    $this->notifyByFirebase('إشعار جديد', 'هناك طلب جديد خاص بك', $tokens, $data);
                    Log::info('Notification Sent to Firebase', ['tokens' => $tokens, 'data' => $data]);

                } else {
                    Log::error('No device tokens for captain', ['captain_id' => $captain->id]);
                }
                // Schedule to make the captain available after order duration

                $userpackage = UserPackage::with('package')->find($order->user_package_id);
                $product = $order->products->first();

                $duration = $product?->duration ?? $userpackage?->package?->duration;

                dispatch(new MakeCaptainAvailableJob($captain->id))
                    ->delay(now()->addMinutes($this->convertTimeToMinutes($duration)));
//                MakeCaptainAvailableJob::dispatch($captain->id)
//                    ->delay(now()->addMinutes($this->convertTimeToMinutes($order->products->first()->duration)));

                $this->info("Assigned Captain ID {$captain->id} to Order ID {$order->id}");
            } else {
                $this->info("No available captains for Order ID {$order->id}");
            }
        }

        $this->info('Finished processing unassigned orders.');
    }

    /**
     * Helper function to convert HH:MM duration to minutes.
     */
    private function convertTimeToMinutes($duration)
    {
        list($hours, $minutes) = explode(':', $duration);
        return ($hours * 60) + $minutes;
    }
}
