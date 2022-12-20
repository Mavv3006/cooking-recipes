<?php

namespace App\DTOs\Creating;

use App\DTOs\DTO;

class RecipeDataDTO extends DTO
{
    public function __construct(
        public readonly string $title,
        public readonly string $description,
        public readonly string $difficulty,
    ) {
    }
}
