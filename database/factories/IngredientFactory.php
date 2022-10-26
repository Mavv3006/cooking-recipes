<?php

namespace Database\Factories;

use App\Models\Ingredient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ingredient>
 */
class IngredientFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'uom' => fake()->randomElement(['g', 'kg', 'ea', 'l', 'ml', 'TL', 'EL'])
        ];
    }
}
