<?php

namespace Database\Factories;

use App\Models\RecipeTimes;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RecipeTimes>
 */
class RecipeTimesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'duration' => fake()->randomFloat(2, 5, 100)
        ];
    }
}
