<?php

namespace Database\Seeders;

use App\Models\Choice;
use Illuminate\Database\Seeder;

class ChoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $choices = [
            [
                'name' => 'تلميع السيارة',
                'name_en' => 'Car Polish',
                'service_price' => 50.00,
            ],
            [
                'name' => 'تنظيف المحرك',
                'name_en' => 'Engine Cleaning',
                'service_price' => 80.00,
            ],
            [
                'name' => 'تنظيف الجنوط',
                'name_en' => 'Wheel Cleaning',
                'service_price' => 30.00,
            ],
            [
                'name' => 'تلميع الزجاج',
                'name_en' => 'Glass Polish',
                'service_price' => 40.00,
            ],
            [
                'name' => 'تنظيف الفرش',
                'name_en' => 'Upholstery Cleaning',
                'service_price' => 60.00,
            ],
            [
                'name' => 'إزالة الروائح',
                'name_en' => 'Odor Removal',
                'service_price' => 45.00,
            ],
        ];

        foreach ($choices as $choice) {
            Choice::create($choice);
        }
    }
}
