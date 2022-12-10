<?php

namespace App\Services;

use App\DTOs\Creating\RecipeDataDTO;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class RecipeService
{
    public function create(User $user, RecipeDataDTO $data): Recipe
    {
        Log::debug('Create recipe');
        $merge = array_merge((array)$data, ['user_id' => $user->id]);
        $recipe = Recipe::create($merge);
        Log::info('Recipe created: ' . $recipe->id);
        return $recipe;
    }

    public function update(Recipe $recipe, RecipeDataDTO $data): void
    {
        Log::debug('Update recipe');
        $merge = array_merge((array)$data, ['user_id' => $recipe->user_id]);
        $recipe->update($merge);
        $recipe->save();
        Log::info('Recipe updated: ' . $recipe->id);
    }
}
