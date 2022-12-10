<?php

namespace App\Services;

use App\DTOs\Creating\RecipeIngredientDTO;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeIngredient;
use Illuminate\Support\Facades\Log;

class RecipeIngredientService
{
    public function create(Recipe $recipe, RecipeIngredientDTO $data): void
    {
        Log::debug('Create ingredients');
        Log::debug('ingredients: ' . json_encode((array)$data));
        $this->createIngredients($data, $recipe);
        Log::info('all recipe ingredients created');
    }

    public function update(Recipe $recipe, RecipeIngredientDTO $data): void
    {
        Log::debug('Update ingredients');
        Log::debug('ingredients: ' . json_encode((array)$data));
        RecipeIngredient::where('recipe_id', $recipe->id)->delete();
        $this->createIngredients($data, $recipe);
        Log::info('all recipe ingredients created');
    }

    private function createIngredients(RecipeIngredientDTO $data, Recipe $recipe): void
    {
        foreach ($data as $request_ingredient) {
            $individual_components = preg_split('/\s/', $request_ingredient['description']);
            Log::debug(json_encode($individual_components));
            $ingredient = Ingredient::firstOrCreate([
                'name' => $individual_components[2],
                'uom' => $individual_components[1]
            ]);
            $recipe_ingredient_values = [
                'quantity' => $individual_components[0],
                'recipe_id' => $recipe->id,
                'ingredient_id' => $ingredient->id
            ];
            $created_recipe_ingredient = RecipeIngredient::create($recipe_ingredient_values);
            Log::debug(
                'Recipe Ingredient for recipe ' . $created_recipe_ingredient->recipe_id . ' and ingredient ' . $created_recipe_ingredient->ingredient_id . ' created.'
            );
        }
    }
}
