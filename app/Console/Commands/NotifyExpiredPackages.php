<?php

namespace App\Console\Commands;

use App\Helper\Helper;
use App\Models\UserPackage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class NotifyExpiredPackages extends Command
{
    use Helper;

    protected $signature = 'packages:notify-expired';
    protected $description = 'Notify users about expired or expiring packages';

    public function handle()
    {
        // Update expired packages
        $expiredPackages = UserPackage::expired()->get();
        foreach ($expiredPackages as $package) {
            $package->update(['status' => 'expired']);
        }

        // Update used up packages
        $usedUpPackages = UserPackage::usedUp()->get();
        foreach ($usedUpPackages as $package) {
            $package->update(['status' => 'used_up']);
        }

        // Find packages expiring in 3 days that haven't been notified
        $expiringPackages = UserPackage::where('status', 'active')
            ->where('expiry_date', '<=', now()->addDays(3))
            ->where('expiry_date', '>', now())
            ->where('notified_expired', false)
            ->with(['user', 'package'])
            ->get();

        $this->info("Found {$expiringPackages->count()} packages expiring soon");

        foreach ($expiringPackages as $userPackage) {
            $this->sendExpirationNotification($userPackage);
            $userPackage->update(['notified_expired' => true]);
        }

        // Find packages that expired today and haven't been notified
        $expiredToday = UserPackage::where('expiry_date', now()->toDateString())
            ->where('notified_expired', false)
            ->with(['user', 'package'])
            ->get();

        $this->info("Found {$expiredToday->count()} packages expired today");

        foreach ($expiredToday as $userPackage) {
            $this->sendExpiredNotification($userPackage);
            $userPackage->update([
                'status' => 'expired',
                'notified_expired' => true
            ]);
        }

        $this->info('Finished processing package notifications');
    }

    private function sendExpirationNotification($userPackage)
    {
        $user = $userPackage->user;
        $package = $userPackage->package;
        $daysRemaining = $userPackage->getDaysRemaining();

        app()->setLocale($user->preferred_language ?? 'ar');
        $tokens = $user->deviceTokens->pluck('token')->toArray();

        if ($tokens) {
            $title = __('general.package_expiring_soon');
            $message = __('general.package_expires_in_days', [
                'package' => $package->getCurrentNameAttribute(),
                'days' => $daysRemaining
            ]);

            $data = [
                'type' => 'package_expiring',
                'user_package_id' => $userPackage->id,
                'days_remaining' => $daysRemaining
            ];

            $this->notifyByFirebase($title, $message, $tokens, $data);
            Log::info('Package expiration notification sent', [
                'user_id' => $user->id,
                'package_id' => $userPackage->id,
                'days_remaining' => $daysRemaining
            ]);
        }
    }

    private function sendExpiredNotification($userPackage)
    {
        $user = $userPackage->user;
        $package = $userPackage->package;

        app()->setLocale($user->preferred_language ?? 'ar');
        $tokens = $user->deviceTokens->pluck('token')->toArray();

        if ($tokens) {
            $title = __('general.package_expired');
            $message = __('general.package_has_expired', [
                'package' => $package->getCurrentNameAttribute()
            ]);

            $data = [
                'type' => 'package_expired',
                'user_package_id' => $userPackage->id
            ];

            $this->notifyByFirebase($title, $message, $tokens, $data);
            Log::info('Package expired notification sent', [
                'user_id' => $user->id,
                'package_id' => $userPackage->id
            ]);
        }
    }
}