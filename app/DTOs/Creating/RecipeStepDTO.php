<?php

namespace App\DTOs\Creating;

use App\DTOs\DTO;
use Countable;

class RecipeStepDTO extends DTO implements Countable
{
    /**
     * @param array $elements An array consisting of {@code SingleRecipeStepDTO} objects
     * @see SingleRecipeStepDTO
     */
    public function __construct(
        public readonly array $elements
    ) {
    }

    public function toArray(): array
    {
        return ['steps' => $this->elements];
    }

    public function count(): int
    {
        return sizeof($this->elements);
    }
}
