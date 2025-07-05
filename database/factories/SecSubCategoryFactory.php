<?php

namespace Database\Factories;

use App\Models\FirstSubCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SecSubCategory>
 */
class SecSubCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->deviceModelName,
            'company_id' => FirstSubCategory::inRandomOrder()->first()->id,
        ];
    }
}
