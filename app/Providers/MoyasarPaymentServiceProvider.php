<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\ServiceProvider;

class MoyasarPaymentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
//    public function boot(): void
//    {
//        // Retrieve keys from settings model
//        $settings = Setting::first();
//        if ($settings) {
//            config([
//                'services.moyasar.key' => $settings->publishable_key,
//                'services.moyasar.secret' => $settings->secret_key,
//            ]);
//        }
//    }
}
