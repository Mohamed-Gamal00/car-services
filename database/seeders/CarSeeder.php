<?php

namespace Database\Seeders;

use App\Models\Car;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
{
    public function run(): void
    {
        $cars = [
            // Toyota
            [
                'brand_ar' => 'تويوتا',
                'brand_en' => 'Toyota',
                'model_ar' => 'كامري',
                'model_en' => 'Camry',
                'year' => '2024',
                'color' => null,
                'is_active' => true,
            ],
            [
                'brand_ar' => 'تويوتا',
                'brand_en' => 'Toyota',
                'model_ar' => 'كورولا',
                'model_en' => 'Corolla',
                'year' => '2024',
                'color' => null,
                'is_active' => true,
            ],
            [
                'brand_ar' => 'تويوتا',
                'brand_en' => 'Toyota',
                'model_ar' => 'برادو',
                'model_en' => 'Prado',
                'year' => '2024',
                'color' => null,
                'is_active' => true,
            ],
            [
                'brand_ar' => 'تويوتا',
                'brand_en' => 'Toyota',
                'model_ar' => 'هايلكس',
                'model_en' => 'Hilux',
                'year' => '2024',
                'color' => null,
                'is_active' => true,
            ],
            [
                'brand_ar' => 'تويوتا',
                'brand_en' => 'Toyota',
                'model_ar' => 'ياريس',
                'model_en' => 'Yaris',
                'year' => '2024',
                'color' => null,
                'is_active' => true,
            ],
            
            // Honda
            [
                'brand_ar' => 'هوندا',
                'brand_en' => 'Honda',
                'model_ar' => 'سيفيك',
                'model_en' => 'Civic',
                'year' => '2024',
                'color' => null,
                'is_active' => true,
            ],
            [
                'brand_ar' => 'هوندا',
                'brand_en' => 'Honda',
                'model_ar' => 'أكورد',
                'model_en' => 'Accord',
                'year' => '2024',
                'color' => null,
                'is_active' => true,
            ],
            [
                'brand_ar' => 'هوندا',
                'brand_en' => 'Honda',
                'model_ar' => 'سي آر في',
                'model_en' => 'CR-V',
                'year' => '2024',
                'color' => null,
                'is_active' => true,
            ],
            [
                'brand_ar' => 'هوندا',
                'brand_en' => 'Honda',
                'model_ar' => 'بايلوت',
                'model_en' => 'Pilot',
                'year' => '2024',
                'color' => null,
                'is_active' => true,
            ],
            
            // Hyundai
            [
                'brand_ar' => 'هيونداي',
                'brand_en' => 'Hyundai',
                'model_ar' => 'النترا',
                'model_en' => 'Elantra',
                'year' => '2024',
                'color' => null,
                'is_active' => true,
            ],
            [
                'brand_ar' => 'هيونداي',
                'brand_en' => 'Hyundai',
                'model_ar' => 'سوناتا',
                'model_en' => 'Sonata',
                'year' => '2024',
                'color' => null,
                'is_active' => true,
            ],
            [
                'brand_ar' => 'هيونداي',
                'brand_en' => 'Hyundai',
                'model_ar' => 'توسان',
                'model_en' => 'Tucson',
                'year' => '2024',
                'color' => null,
                'is_active' => true,
            ],
            [
                'brand_ar' => 'هيونداي',
                'brand_en' => 'Hyundai',
                'model_ar' => 'سنتافي',
                'model_en' => 'Santa Fe',
                'year' => '2024',
                'color' => null,
                'is_active' => true,
            ],
            
            // Nissan
            [
                'brand_ar' => 'نيسان',
                'brand_en' => 'Nissan',
                'model_ar' => 'التيما',
                'model_en' => 'Altima',
                'year' => '2024',
                'color' => null,
                'is_active' => true,
            ],
            [
                'brand_ar' => 'نيسان',
                'brand_en' => 'Nissan',
                'model_ar' => 'سنترا',
                'model_en' => 'Sentra',
                'year' => '2024',
                'color' => null,
                'is_active' => true,
            ],
            [
                'brand_ar' => 'نيسان',
                'brand_en' => 'Nissan',
                'model_ar' => 'اكس تريل',
                'model_en' => 'X-Trail',
                'year' => '2024',
                'color' => null,
                'is_active' => true,
            ],
            [
                'brand_ar' => 'نيسان',
                'brand_en' => 'Nissan',
                'model_ar' => 'باترول',
                'model_en' => 'Patrol',
                'year' => '2024',
                'color' => null,
                'is_active' => true,
            ],
            
            // BMW
            [
                'brand_ar' => 'بي ام دبليو',
                'brand_en' => 'BMW',
                'model_ar' => 'الفئة الثالثة',
                'model_en' => '3 Series',
                'year' => '2024',
                'color' => null,
                'is_active' => true,
            ],
            [
                'brand_ar' => 'بي ام دبليو',
                'brand_en' => 'BMW',
                'model_ar' => 'الفئة الخامسة',
                'model_en' => '5 Series',
                'year' => '2024',
                'color' => null,
                'is_active' => true,
            ],
            [
                'brand_ar' => 'بي ام دبليو',
                'brand_en' => 'BMW',
                'model_ar' => 'اكس 3',
                'model_en' => 'X3',
                'year' => '2024',
                'color' => null,
                'is_active' => true,
            ],
            [
                'brand_ar' => 'بي ام دبليو',
                'brand_en' => 'BMW',
                'model_ar' => 'اكس 5',
                'model_en' => 'X5',
                'year' => '2024',
                'color' => null,
                'is_active' => true,
            ],
            
            // Mercedes
            [
                'brand_ar' => 'مرسيدس بنز',
                'brand_en' => 'Mercedes-Benz',
                'model_ar' => 'الفئة سي',
                'model_en' => 'C-Class',
                'year' => '2024',
                'color' => null,
                'is_active' => true,
            ],
            [
                'brand_ar' => 'مرسيدس بنز',
                'brand_en' => 'Mercedes-Benz',
                'model_ar' => 'الفئة اي',
                'model_en' => 'E-Class',
                'year' => '2024',
                'color' => null,
                'is_active' => true,
            ],
            [
                'brand_ar' => 'مرسيدس بنز',
                'brand_en' => 'Mercedes-Benz',
                'model_ar' => 'جي ال سي',
                'model_en' => 'GLC',
                'year' => '2024',
                'color' => null,
                'is_active' => true,
            ],
            [
                'brand_ar' => 'مرسيدس بنز',
                'brand_en' => 'Mercedes-Benz',
                'model_ar' => 'جي ال اي',
                'model_en' => 'GLE',
                'year' => '2024',
                'color' => null,
                'is_active' => true,
            ],
            
            // Lexus
            [
                'brand_ar' => 'لكزس',
                'brand_en' => 'Lexus',
                'model_ar' => 'اي اس',
                'model_en' => 'ES',
                'year' => '2024',
                'color' => null,
                'is_active' => true,
            ],
            [
                'brand_ar' => 'لكزس',
                'brand_en' => 'Lexus',
                'model_ar' => 'ار اكس',
                'model_en' => 'RX',
                'year' => '2024',
                'color' => null,
                'is_active' => true,
            ],
            [
                'brand_ar' => 'لكزس',
                'brand_en' => 'Lexus',
                'model_ar' => 'ال اكس',
                'model_en' => 'LX',
                'year' => '2024',
                'color' => null,
                'is_active' => true,
            ],
            
            // Ford
            [
                'brand_ar' => 'فورد',
                'brand_en' => 'Ford',
                'model_ar' => 'اكسبلورر',
                'model_en' => 'Explorer',
                'year' => '2024',
                'color' => null,
                'is_active' => true,
            ],
            [
                'brand_ar' => 'فورد',
                'brand_en' => 'Ford',
                'model_ar' => 'ايدج',
                'model_en' => 'Edge',
                'year' => '2024',
                'color' => null,
                'is_active' => true,
            ],
            [
                'brand_ar' => 'فورد',
                'brand_en' => 'Ford',
                'model_ar' => 'اف 150',
                'model_en' => 'F-150',
                'year' => '2024',
                'color' => null,
                'is_active' => true,
            ],
            
            // Chevrolet
            [
                'brand_ar' => 'شيفروليه',
                'brand_en' => 'Chevrolet',
                'model_ar' => 'تاهو',
                'model_en' => 'Tahoe',
                'year' => '2024',
                'color' => null,
                'is_active' => true,
            ],
            [
                'brand_ar' => 'شيفروليه',
                'brand_en' => 'Chevrolet',
                'model_ar' => 'سوبربان',
                'model_en' => 'Suburban',
                'year' => '2024',
                'color' => null,
                'is_active' => true,
            ],
            
            // GMC
            [
                'brand_ar' => 'جي ام سي',
                'brand_en' => 'GMC',
                'model_ar' => 'يوكن',
                'model_en' => 'Yukon',
                'year' => '2024',
                'color' => null,
                'is_active' => true,
            ],
            [
                'brand_ar' => 'جي ام سي',
                'brand_en' => 'GMC',
                'model_ar' => 'سييرا',
                'model_en' => 'Sierra',
                'year' => '2024',
                'color' => null,
                'is_active' => true,
            ],
        ];

        foreach ($cars as $car) {
            Car::updateOrCreate(
                [
                    'brand_ar' => $car['brand_ar'],
                    'model_ar' => $car['model_ar'],
                ],
                $car
            );
        }
    }
}
