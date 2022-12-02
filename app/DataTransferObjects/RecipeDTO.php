<?php

namespace App\DataTransferObjects;

use App\Models\Recipe;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class RecipeDTO
{
    public function __construct(
        public readonly \Illuminate\Support\Collection $ingredients,
        public readonly Collection $steps,
        public readonly Collection $comments,
        public readonly Collection $times,
        public readonly RatingsDTO $ratings,
        public readonly bool $isFavorite,
        public readonly Recipe $recipe,
        public readonly ?Collection $timeUnitOfMeasures
    ) {
    }

    public function toArray(): array
    {
        return [
            'recipe' => $this->recipe,
            'ingredients' => $this->ingredients,
            'steps' => $this->steps,
            'user' => $this->recipe->user()->first(),
            'isFavorite' => $this->isFavorite,
            'isLoggedIn' => Auth::check(),
            'comments' => $this->comments,
            'ratings' => $this->ratings,
            'times' => $this->times,
            'timeUnitOfMeasures' => $this->timeUnitOfMeasures
        ];
    }
}
