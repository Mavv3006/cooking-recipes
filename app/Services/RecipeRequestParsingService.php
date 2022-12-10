<?php

namespace App\Services;

use App\DTOs\Creating\RecipeDataDTO;
use App\DTOs\Creating\RecipeIngredientDTO;
use App\DTOs\Creating\RecipeRequestWrapperDTO;
use App\DTOs\Creating\RecipeStepDTO;
use App\DTOs\Creating\RecipeTimeDTO;
use App\DTOs\Creating\SingleRecipeStepDTO;
use App\DTOs\Creating\SingleTimeDTO;

class RecipeRequestParsingService
{
    public function extractDataIntoDto(array $data): RecipeRequestWrapperDTO
    {
        $recipe = new RecipeDataDTO($data['title'], $data['description'], $data['difficulty']);
        $steps = $this->extractRecipeStepDTO($data['steps']);
        $ingredients = new RecipeIngredientDTO($data['ingredients']);
        $recipeTimeDto = $this->extractRecipeTimeDTO($data['times']);
        return new RecipeRequestWrapperDTO($recipe, $steps, $ingredients, $recipeTimeDto);
    }

    private function extractRecipeStepDTO(array $data): RecipeStepDTO
    {
        $steps = array();
        foreach ($data as $step) {
            $description = $step['description'];
            $id = $step['id'] ?? null;
            $steps[] = new SingleRecipeStepDTO($description, $id);
        }
        return new RecipeStepDTO($steps);
    }

    private function extractRecipeTimeDTO(array $data): RecipeTimeDTO
    {
        $times = array();
        foreach ($data as $time) {
            $times[] = new SingleTimeDTO($time['id'], $time['uom_id'], $time['duration']);
        }
        return new RecipeTimeDTO($times);
    }
}
