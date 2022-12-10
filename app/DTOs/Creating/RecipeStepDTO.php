<?php

namespace App\DTOs\Creating;

use App\DTOs\DTO;
use Countable;

class RecipeStepDTO implements DTO, Countable
{

    public function __construct(
        public readonly array $steps
    ) {
    }

    public function toArray(): array
    {
        return ['steps' => $this->steps];
    }

    public function count(): int
    {
        return sizeof($this->steps);
    }
}
