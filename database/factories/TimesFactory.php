<?php

namespace Database\Factories;

use App\Models\Times;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Times>
 */
class TimesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->words(2, true)
        ];
    }
}
