<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PollenForecastFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'date' => fake()->date(),
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
            'tree_upi' => fake()->numberBetween(-10000, 10000),
            'grass_upi' => fake()->numberBetween(-10000, 10000),
            'weed_upi' => fake()->numberBetween(-10000, 10000),
            'pollen_data' => '{}',
        ];
    }
}
