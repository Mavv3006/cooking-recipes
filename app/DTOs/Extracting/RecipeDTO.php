<?php

namespace App\DTOs\Extracting;

use App\DTOs\DTO;
use App\Models\Recipe;
use Illuminate\Database\Eloquent\Collection;

class RecipeDTO extends DTO
{
    public function __construct(
        public readonly \Illuminate\Support\Collection $ingredients,
        public readonly Collection $steps,
        public readonly Collection $comments,
        public readonly Collection $times,
        public readonly RatingsDTO $ratings,
        public readonly bool $isFavorite,
        public readonly Recipe $recipe,
        public readonly ?Collection $timeUnitOfMeasures,
        public readonly Collection $images
    ) {
    }
}
