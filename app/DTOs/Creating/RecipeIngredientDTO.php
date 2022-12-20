<?php

namespace App\DTOs\Creating;

use App\DTOs\DTO;
use Countable;

class RecipeIngredientDTO extends DTO implements Countable
{
    public function __construct(
        public readonly array $elements
    ) {
    }

    public function toArray(): array
    {
        return ['ingredients' => $this->elements];
    }

    public function count(): int
    {
        return sizeof($this->elements);
    }
}
