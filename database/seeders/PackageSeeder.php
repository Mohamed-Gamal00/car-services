<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\PackageFeature;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        // Basic Package
        $basicPackage = Package::create([
            'name' => 'الباقة الأساسية',
            'name_en' => 'Basic Package',
            'description' => 'باقة أساسية تشمل 5 غسلات',
            'description_en' => 'Basic package includes 5 washes',
            'wash_count' => 5,
            'price' => 100.00,
            'duration' => '00:30',
            'validity_days' => 30,
            'sort_order' => 1,
        ]);

        $basicFeatures = [
            ['feature' => 'غسيل خارجي', 'feature_en' => 'Exterior wash'],
            ['feature' => 'تجفيف', 'feature_en' => 'Drying'],
            ['feature' => 'تنظيف الإطارات', 'feature_en' => 'Tire cleaning'],
        ];

        foreach ($basicFeatures as $index => $feature) {
            PackageFeature::create([
                'package_id' => $basicPackage->id,
                'feature' => $feature['feature'],
                'feature_en' => $feature['feature_en'],
                'sort_order' => $index + 1,
            ]);
        }

        // Premium Package
        $premiumPackage = Package::create([
            'name' => 'الباقة المميزة',
            'name_en' => 'Premium Package',
            'description' => 'باقة مميزة تشمل 10 غسلات شاملة',
            'description_en' => 'Premium package includes 10 complete washes',
            'wash_count' => 10,
            'price' => 180.00,
            'duration' => '00:45',
            'validity_days' => 60,
            'sort_order' => 2,
        ]);

        $premiumFeatures = [
            ['feature' => 'غسيل خارجي وداخلي', 'feature_en' => 'Interior & exterior wash'],
            ['feature' => 'تنظيف الإطارات والجنوط', 'feature_en' => 'Tire & rim cleaning'],
            ['feature' => 'تنظيف المحرك', 'feature_en' => 'Engine cleaning'],
            ['feature' => 'تعطير السيارة', 'feature_en' => 'Car fragrance'],
        ];

        foreach ($premiumFeatures as $index => $feature) {
            PackageFeature::create([
                'package_id' => $premiumPackage->id,
                'feature' => $feature['feature'],
                'feature_en' => $feature['feature_en'],
                'sort_order' => $index + 1,
            ]);
        }

        // VIP Package
        $vipPackage = Package::create([
            'name' => 'باقة VIP',
            'name_en' => 'VIP Package',
            'description' => 'باقة VIP تشمل 15 غسلة مع خدمات إضافية',
            'description_en' => 'VIP package includes 15 washes with additional services',
            'wash_count' => 15,
            'price' => 250.00,
            'duration' => '01:00',
            'validity_days' => 90,
            'sort_order' => 3,
        ]);

        $vipFeatures = [
            ['feature' => 'غسيل شامل داخلي وخارجي', 'feature_en' => 'Complete interior & exterior wash'],
            ['feature' => 'تطبيق الشمع', 'feature_en' => 'Wax application'],
            ['feature' => 'تنظيف المحرك', 'feature_en' => 'Engine cleaning'],
            ['feature' => 'تعطير السيارة', 'feature_en' => 'Car fragrance'],
            ['feature' => 'خدمة منزلية مجانية', 'feature_en' => 'Free home service'],
        ];

        foreach ($vipFeatures as $index => $feature) {
            PackageFeature::create([
                'package_id' => $vipPackage->id,
                'feature' => $feature['feature'],
                'feature_en' => $feature['feature_en'],
                'sort_order' => $index + 1,
            ]);
        }
    }
}