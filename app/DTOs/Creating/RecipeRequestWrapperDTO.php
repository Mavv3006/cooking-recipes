<?php

namespace App\DTOs\Creating;

use App\DTOs\DTO;

class RecipeRequestWrapperDTO extends DTO
{
    public function __construct(
        public readonly RecipeDataDTO $recipe,
        public readonly RecipeStepDTO $steps,
        public readonly RecipeIngredientDTO $ingredients,
        public readonly RecipeTimeDTO $times,
    ) {
    }
}
