<?php

namespace Tests\Unit;

use App\Models\Recipe;
use Database\Seeders\RecipeSeeder;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RecipeQueryTest extends TestCase
{
    public function test_example()
    {
        $this->seed(RecipeSeeder::class);
        $recipe_id = 1;

        $ingredients_json = DB::table('recipes')
            ->join('recipe_ingredients', 'recipes.id', '=', 'recipe_ingredients.recipe_id')
            ->join('ingredients', 'recipe_ingredients.ingredient_id', '=', 'ingredients.id')
            ->where('recipes.id', '=', $recipe_id)
            ->select(['quantity', 'uom', 'name'])
            ->get()
            ->toJson();
    }

    public function test_get_author_of_recipe()
    {
        $this->seed(RecipeSeeder::class);
        $recipe = Recipe::find(1);

        $author = $recipe->user()->first();

        var_dump($author->name);
    }
}
