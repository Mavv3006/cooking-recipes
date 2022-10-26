<?php

namespace Database\Factories;

use App\Models\Recipe;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Recipe>
 */
class RecipeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->name,
            'description' => $this->faker->text(),
            'difficulty' => $this->faker->randomElement(['easy', 'normal', 'hard']),
        ];
    }
}
