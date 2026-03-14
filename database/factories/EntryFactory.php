<?php

namespace Database\Factories;

use App\Models\PollenForecast;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EntryFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'pollen_forecast_id' => PollenForecast::factory(),
            'date' => fake()->date(),
            'symptoms_severity' => fake()->numberBetween(-10000, 10000),
            'symptoms' => '{}',
            'medication_taken' => fake()->boolean(),
            'notes' => fake()->text(),
        ];
    }
}
