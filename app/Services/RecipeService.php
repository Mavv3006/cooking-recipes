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
        $model = $user->recipes()->create((array)$data);
        $recipe = new Recipe($model->toArray());
        Log::info('Recipe created: ' . $recipe->id);
        return $recipe;
    }
}
