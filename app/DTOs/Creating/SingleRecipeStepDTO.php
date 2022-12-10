<?php

namespace App\DTOs\Creating;

use App\DTOs\DTO;

class SingleRecipeStepDTO implements DTO
{
    public function __construct(
        public readonly string $description,
        public readonly ?int $id
    ) {
    }
}
