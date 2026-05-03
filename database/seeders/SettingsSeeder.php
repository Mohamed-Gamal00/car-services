<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        Setting::updateOrCreate(
            ['id' => 1],
            [
                'website_name' => 'كويك كلين',
                'website_name_en' => 'Quick Clean',
                'address' => 'الرياض، المملكة العربية السعودية',
                'address_en' => 'Riyadh, Saudi Arabia',
                'subscription_title' => 'خدمات غسيل وتنظيف السيارات',
                'subscription_title_en' => 'Car Wash and Cleaning Services',
                'email' => 'info@quickclean.com',
                'phone_number' => '966500000000',
                'whatsaap' => '966500000000',
                'publishable_key' => "pk_test_r6D1NBB77sSPEn2i3oPWjdsJGoxKL639KJC1qsvF",
                'secret_key' => "sk_test_sGgkjPMTHcREvhh7yL74Xn6BkGPfk3P3A89DyYbn",
                'sms_api_key' => "sms api key",
                'sms_user_name' => "sms user name",
                'sernder' => 'QuickClean',
                'working_strat_time' => '08:00',
                'working_end_time' => '22:00',
                'start_rest_time' => '12:00',
                'end_rest_time' => '13:00',
                'logo' => null,
                'image' => null,
            ]
        );
    }
}