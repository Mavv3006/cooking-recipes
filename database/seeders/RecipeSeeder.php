<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeSteps;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Exception
     */
    public function run()
    {
        Recipe::factory()
            ->count(10)
            ->forUser()
            ->has(RecipeSteps::factory()->count(4))
            ->hasAttached(
                Ingredient::factory()->count(3),
                ['quantity' => 1, 'created_at' => now(), 'updated_at' => now()]
            )
            ->create();
    }
}
