<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeSteps;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run(): void
    {
        $user_count = User::all()->count();
        $has_user = $user_count > 0;

        $recipes = Recipe::factory()
            ->count(10)
            ->for($has_user ? User::all()->random(1)->first() : User::factory()->create())
            ->has(RecipeSteps::factory()->count(4))
            ->hasAttached(
                Ingredient::factory()->count(3),
                ['quantity' => 1, 'created_at' => now(), 'updated_at' => now()]
            )
            ->create();

        foreach ($recipes as $recipe) {
            Comment::factory()
                ->count(5)
                ->state(new Sequence(
                    fn($sequence) => ['user_id' => User::all()->random(1)->first()->id]
                ))
                ->create([
                    'recipe_id' => $recipe->id
                ]);
        }
    }
}
