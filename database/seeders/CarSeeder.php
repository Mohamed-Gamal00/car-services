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
            ['brand' => 'Toyota', 'model' => 'Camry'],
            ['brand' => 'Toyota', 'model' => 'Corolla'],
            ['brand' => 'Toyota', 'model' => 'Prado'],
            ['brand' => 'Toyota', 'model' => 'Hilux'],
            ['brand' => 'Toyota', 'model' => 'Yaris'],
            
            // Honda
            ['brand' => 'Honda', 'model' => 'Civic'],
            ['brand' => 'Honda', 'model' => 'Accord'],
            ['brand' => 'Honda', 'model' => 'CR-V'],
            ['brand' => 'Honda', 'model' => 'Pilot'],
            
            // Hyundai
            ['brand' => 'Hyundai', 'model' => 'Elantra'],
            ['brand' => 'Hyundai', 'model' => 'Sonata'],
            ['brand' => 'Hyundai', 'model' => 'Tucson'],
            ['brand' => 'Hyundai', 'model' => 'Santa Fe'],
            
            // Nissan
            ['brand' => 'Nissan', 'model' => 'Altima'],
            ['brand' => 'Nissan', 'model' => 'Sentra'],
            ['brand' => 'Nissan', 'model' => 'X-Trail'],
            ['brand' => 'Nissan', 'model' => 'Patrol'],
            
            // BMW
            ['brand' => 'BMW', 'model' => '3 Series'],
            ['brand' => 'BMW', 'model' => '5 Series'],
            ['brand' => 'BMW', 'model' => 'X3'],
            ['brand' => 'BMW', 'model' => 'X5'],
            
            // Mercedes
            ['brand' => 'Mercedes-Benz', 'model' => 'C-Class'],
            ['brand' => 'Mercedes-Benz', 'model' => 'E-Class'],
            ['brand' => 'Mercedes-Benz', 'model' => 'GLC'],
            ['brand' => 'Mercedes-Benz', 'model' => 'GLE'],
            
            // Lexus
            ['brand' => 'Lexus', 'model' => 'ES'],
            ['brand' => 'Lexus', 'model' => 'RX'],
            ['brand' => 'Lexus', 'model' => 'LX'],
            
            // Ford
            ['brand' => 'Ford', 'model' => 'Explorer'],
            ['brand' => 'Ford', 'model' => 'Edge'],
            ['brand' => 'Ford', 'model' => 'F-150'],
        ];

        foreach ($cars as $car) {
            Car::create($car);
        }
    }
}