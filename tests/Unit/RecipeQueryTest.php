<?php

namespace Tests\Unit;

use App\Models\Recipe;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RecipeQueryTest extends TestCase
{
    use RefreshDatabase;

    public function test_example()
    {
        $this->seed(DatabaseSeeder::class);
        $recipe_id = 1;

        $ingredients_json = DB::table('recipes')
            ->join('recipe_ingredients', 'recipes.id', '=', 'recipe_ingredients.recipe_id')
            ->join('ingredients', 'recipe_ingredients.ingredient_id', '=', 'ingredients.id')
            ->where('recipes.id', '=', $recipe_id)
            ->select(['quantity', 'uom', 'name'])
            ->get()
            ->toJson();

        foreach (json_decode($ingredients_json) as $item) {
            $this->assertNotNull($item->quantity);
            $this->assertNotNull($item->uom);
            $this->assertNotNull($item->name);
        }
    }

    public function test_get_author_of_recipe()
    {
        $author = Recipe::factory()->for(User::factory())->create()->user()->first();

        $this->assertInstanceOf(User::class, $author);
        $this->assertNotNull($author->name);
        $this->assertIsString($author->name);
    }
}
