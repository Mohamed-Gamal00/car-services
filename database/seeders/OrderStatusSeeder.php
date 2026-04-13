<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            [
                'name' => 'قيد الانتظار',
                'name_en' => 'Pending',
                'color' => '#ffc107',
                'sort_order' => 1,
            ],
            [
                'name' => 'تم التأكيد',
                'name_en' => 'Confirmed',
                'color' => '#17a2b8',
                'sort_order' => 2,
            ],
            [
                'name' => 'تم التعيين',
                'name_en' => 'Assigned',
                'color' => '#007bff',
                'sort_order' => 3,
            ],
            [
                'name' => 'في الطريق',
                'name_en' => 'On the way',
                'color' => '#fd7e14',
                'sort_order' => 4,
            ],
            [
                'name' => 'وصل الكابتن',
                'name_en' => 'Captain arrived',
                'color' => '#6f42c1',
                'sort_order' => 5,
            ],
            [
                'name' => 'جاري العمل',
                'name_en' => 'In progress',
                'color' => '#20c997',
                'sort_order' => 6,
            ],
            [
                'name' => 'مكتمل',
                'name_en' => 'Completed',
                'color' => '#28a745',
                'sort_order' => 7,
            ],
            [
                'name' => 'ملغي',
                'name_en' => 'Cancelled',
                'color' => '#dc3545',
                'sort_order' => 8,
            ],
        ];

        foreach ($statuses as $status) {
            OrderStatus::create($status);
        }
    }
}