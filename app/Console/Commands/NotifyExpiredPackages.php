<?php

namespace App\Console\Commands;

use App\Helper\Helper;
use App\Models\User;
use App\Models\UserPackage;
use Carbon\Carbon;
use Illuminate\Console\Command;

class NotifyExpiredPackages extends Command
{
    use Helper;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:expired-packages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify users if their package is expired or about to expire';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::now();
        $threeDaysLater = $today->copy()->addDays(3);

        // باقات انتهت
        $expiredPackages = UserPackage::where('expiry_date', '<', $today)
            ->whereIn('status', ['active','used_up','expired'])
            ->where('notified_expired',0)
            ->get();

        // باقات قربت تنتهي
        $expiringSoonPackages = UserPackage::whereBetween('expiry_date', [$today, $threeDaysLater])
            ->whereIn('status', ['active', 'used_up','expired'])
            ->where('notified_expired',0)
            ->get();

        // إرسال نوتفكيشن للباقات المنتهية
        foreach ($expiredPackages as $package) {
            $user = $package->user;
            if ($user) {
                $this->sendNotification($user, __('general.Your_package_has_expired'), __('general.Please_renew_your_package'));
                $package->update(['notified_expired' => true]);
            }
        }

        // إرسال نوتفكيشن للباقات اللي قربت تنتهي
        foreach ($expiringSoonPackages as $package) {
            $user = $package->user;
            if ($user) {
                $this->sendNotification($user, __('general.Your_package_will_expire_soon'), __('general.You_have_3_days_to_renew'));
                $package->update(['notified_expired' => true]);
            }
        }
    }


    protected function sendNotification(User $user, $title, $body)
    {
        $deviceTokens = $user->devicetokens()->pluck('token');
        if ($deviceTokens->isEmpty()) return;

        app()->setLocale($user->lang ?? 'ar');

        $data = [
            'type' => 'package',
            'action' => 'renewal_reminder'
        ];

        // استخدم نفس ميثود إرسال فايربيز
        $this->notifyByFirebase($title, $body, $deviceTokens->toArray(), $data);
    }
}
