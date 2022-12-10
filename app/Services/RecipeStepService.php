<?php

namespace App\Services;

use App\DTOs\Creating\RecipeStepDTO;
use App\Models\Recipe;
use Illuminate\Support\Facades\Log;

class RecipeStepService
{
    public function create(Recipe $recipe, RecipeStepDTO $data): void
    {
        Log::debug('Create steps');
        Log::debug('steps: ' . json_encode((array)$data));
        $recipe->steps()->createMany((array)$data);
        Log::info('All recipe ' . sizeof($data) . ' steps created');
    }
}
