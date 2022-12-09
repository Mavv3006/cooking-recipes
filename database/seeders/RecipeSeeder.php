<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Ingredient;
use App\Models\Rating;
use App\Models\Recipe;
use App\Models\RecipeSteps;
use App\Models\RecipeTimes;
use App\Models\Times;
use App\Models\TimesUnit;
use App\Models\User;
use Exception;
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
        if (User::all()->count() == 0) {
            User::factory()->create();
        }

        $randomUser = fn() => User::all()->random(1)->first();

        $recipes = Recipe::factory()
            ->count(10)
            ->sequence(fn($sequence) => ['user_id' => $randomUser()])
            ->has(RecipeSteps::factory()->count(4))
            ->hasAttached(
                Ingredient::factory()->count(3),
                ['quantity' => 1, 'created_at' => now(), 'updated_at' => now()]
            )
            ->create();

        foreach ($recipes as $recipe) {
            Comment::factory()
                ->count(5)
                ->sequence(fn($sequence) => ['user_id' => $randomUser()->id])
                ->create(['recipe_id' => $recipe->id]);

            $rating_count = 5;
            $users = User::all()->random($rating_count);
            Rating::factory()
                ->count($rating_count)
                ->sequence(fn($sequence) => ['user_id' => $users[$sequence->index]])
                ->create(['recipe_id' => $recipe->id]);

            $times_count = 3;
            $times = Times::all()->random($times_count);
            RecipeTimes::factory()
                ->count($times_count)
                ->sequence(fn($sequence) => ['times_id' => $times[$sequence->index]])
                ->sequence(fn($sequence) => ['times_unit_id' => TimesUnit::all()->random(1)->first()->id])
                ->create(['recipe_id' => $recipe->id]);
        }
    }
}
