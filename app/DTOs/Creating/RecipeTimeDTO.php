<?php

namespace App\DTOs\Creating;

use App\DTOs\DTO;

class RecipeTimeDTO implements DTO
{
    /**
     * @param array $elements An array consisting of {@code SingleTimeDTO} objects
     * @see SingleTimeDTO
     *
     */
    public function __construct(
        public readonly array $elements
    ) {
    }

    public function toArray(): array
    {
        return ['times' => $this->elements];
    }

    public function count(): int
    {
        return sizeof($this->elements);
    }
}
