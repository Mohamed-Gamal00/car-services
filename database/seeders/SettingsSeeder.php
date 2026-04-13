<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // App Settings
            [
                'key' => 'app_name',
                'value' => 'Quick Clean',
                'type' => 'string',
                'group' => 'app',
                'description' => 'Application name'
            ],
            [
                'key' => 'app_name_ar',
                'value' => 'كويك كلين',
                'type' => 'string',
                'group' => 'app',
                'description' => 'Application name in Arabic'
            ],
            [
                'key' => 'app_logo',
                'value' => null,
                'type' => 'string',
                'group' => 'app',
                'description' => 'Application logo path'
            ],
            
            // Working Hours
            [
                'key' => 'working_hours_start',
                'value' => '08:00',
                'type' => 'string',
                'group' => 'booking',
                'description' => 'Working hours start time'
            ],
            [
                'key' => 'working_hours_end',
                'value' => '22:00',
                'type' => 'string',
                'group' => 'booking',
                'description' => 'Working hours end time'
            ],
            [
                'key' => 'advance_booking_minutes',
                'value' => '30',
                'type' => 'integer',
                'group' => 'booking',
                'description' => 'Minimum advance booking time in minutes'
            ],
            
            // Payment Settings
            [
                'key' => 'currency',
                'value' => 'SAR',
                'type' => 'string',
                'group' => 'payment',
                'description' => 'Default currency'
            ],
            [
                'key' => 'tax_rate',
                'value' => '15',
                'type' => 'integer',
                'group' => 'payment',
                'description' => 'Tax rate percentage'
            ],
            
            // Notification Settings
            [
                'key' => 'firebase_server_key',
                'value' => null,
                'type' => 'string',
                'group' => 'notifications',
                'description' => 'Firebase server key for push notifications'
            ],
            
            // Contact Information
            [
                'key' => 'contact_phone',
                'value' => '+966500000000',
                'type' => 'string',
                'group' => 'contact',
                'description' => 'Contact phone number'
            ],
            [
                'key' => 'contact_email',
                'value' => 'info@quickclean.com',
                'type' => 'string',
                'group' => 'contact',
                'description' => 'Contact email address'
            ],
            [
                'key' => 'contact_address',
                'value' => 'الرياض، المملكة العربية السعودية',
                'type' => 'string',
                'group' => 'contact',
                'description' => 'Contact address'
            ],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}