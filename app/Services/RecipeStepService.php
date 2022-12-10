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

    public function update(Recipe $recipe, RecipeStepDTO $data): void
    {
        /*
         * First of all I need in the RecipeStepDTO the id of the recipe_step table for all the steps that currently
         * exist and should be updated.
         * - If the array in the DTO contains not all the steps currently in the database the ones which are not in
         * the DTO should be deleted.
         * - If there are elements in the DTO which have no ID, then for those elements a new record in the database
         * should be created.
         * The order of the steps is determined by the order of the (in ascending order). This is because a reordering
         * of steps is currently not in scope.
         * */
        // TODO: Update the logic as described in the comment above

        Log::debug('Update steps');
        Log::debug('steps: ', (array)$data);
        $recipe->steps()->delete();
        $recipe->steps()->createMany((array)$data);
        Log::info('All recipe ' . sizeof($data) . ' steps updated');
    }
}
