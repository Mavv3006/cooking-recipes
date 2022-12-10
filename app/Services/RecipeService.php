<?php

namespace App\Services;

use App\DTOs\Creating\RecipeDataDTO;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class RecipeService
{
    public function create(User $user, RecipeDataDTO $data): Model
    {
        Log::debug('Create recipe');
        $recipe = $user->recipes()->create($data->toArray());
        Log::info('Recipe created: ' . $recipe->id);
        return $recipe;
    }
}
