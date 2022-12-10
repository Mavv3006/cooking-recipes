<?php

namespace App\Services;

use App\DTOs\Creating\RecipeTimeDTO;
use App\Models\Recipe;
use App\Models\RecipeTimes;
use Illuminate\Support\Facades\Log;

class RecipeTimeService
{
    public function create(Recipe $recipe, RecipeTimeDTO $dto): void
    {
        Log::debug('Create times');
        Log::debug('times: ' . json_encode((array)$dto));
        foreach ($dto->elements as $time) {
            $recipe_time = RecipeTimes::create([
                'recipe_id' => $recipe->id,
                'times_id' => $time->id,
                'times_unit_id' => $time->uom_id,
                'duration' => $time->duration
            ]);
            Log::debug(
                'Recipe time for recipe ' . $recipe_time->recipe_id . ' and time ' . $recipe_time->times_id . ' with duration ' . $recipe_time->duration . ' created.'
            );
        }
        Log::info('all recipe times created');
    }
}
