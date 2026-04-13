<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            SettingsSeeder::class,
            OrderStatusSeeder::class,
            CarSeeder::class,
            ServiceSeeder::class,
            PackageSeeder::class,
        ]);
    }
}