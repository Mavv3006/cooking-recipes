<?php

namespace Database\Factories;

use App\Models\RecipeSteps;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RecipeSteps>
 */
class RecipeStepsFactory extends Factory
{
    public function definition(): array
    {
        return [
            'description' => fake()->text(),
        ];
    }
}
