<?php

namespace App\Services;

use App\DTOs\Creating\RecipeTimeDTO;
use App\DTOs\Creating\SingleTimeDTO;
use App\Models\Recipe;
use App\Models\RecipeTimes;
use Illuminate\Support\Facades\Log;

class RecipeTimeService
{
    public function create(Recipe $recipe, RecipeTimeDTO $data): void
    {
        Log::debug('Create times');
        Log::debug('times: ' . json_encode((array)$data));
        foreach ($data->elements as $time) {
            $this->createNewTime($recipe, $time);
        }
        Log::info('all recipe times created');
    }

    public function update(Recipe $recipe, RecipeTimeDTO $data): void
    {
        Log::debug('Update times');
        Log::debug('times: ' . json_encode((array)$data));
        $existingTimes = RecipeTimes::where('recipe_id', $recipe->id)->get();

        foreach ($data->elements as $time) {
            $contains = $existingTimes->contains(function ($value, $key) use ($time) {
                return $value->times_id == $time->id;
            });
            if ($contains) {
                $this->handleExistingTime($recipe, $time);
                continue;
            }
            $this->createNewTime($recipe, $time);
        }
        Log::info('all recipe times updated');
    }

    private function createNewTime(Recipe $recipe, SingleTimeDTO $time): void
    {
        RecipeTimes::create([
            'recipe_id' => $recipe->id,
            'times_id' => $time->id,
            'times_unit_id' => $time->uom_id,
            'duration' => $time->duration
        ]);
    }

    private function handleExistingTime(Recipe $recipe, mixed $time): void
    {
        $existingTime = RecipeTimes::where('recipe_id', $recipe->id)->where('times_id', $time->id);
        if ($time->duration > 0) {
            $existingTime->update([
                'times_unit_id' => $time->uom_id,
                'duration' => $time->duration
            ]);
            return;
        }
        $existingTime->delete();
    }
}
