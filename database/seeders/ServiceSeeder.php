<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name' => 'غسيل خارجي',
                'name_en' => 'Exterior Wash',
                'description' => 'غسيل شامل للجزء الخارجي من السيارة',
                'description_en' => 'Complete exterior car wash',
                'price' => 25.00,
                'duration' => '00:30',
                'sort_order' => 1,
            ],
            [
                'name' => 'غسيل داخلي',
                'name_en' => 'Interior Cleaning',
                'description' => 'تنظيف شامل للجزء الداخلي من السيارة',
                'description_en' => 'Complete interior car cleaning',
                'price' => 35.00,
                'duration' => '00:45',
                'sort_order' => 2,
            ],
            [
                'name' => 'غسيل شامل',
                'name_en' => 'Full Service',
                'description' => 'غسيل شامل داخلي وخارجي',
                'description_en' => 'Complete interior and exterior wash',
                'price' => 50.00,
                'duration' => '01:00',
                'sort_order' => 3,
            ],
            [
                'name' => 'غسيل بالشمع',
                'name_en' => 'Wax Service',
                'description' => 'غسيل شامل مع تطبيق الشمع',
                'description_en' => 'Complete wash with wax application',
                'price' => 75.00,
                'duration' => '01:30',
                'sort_order' => 4,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}