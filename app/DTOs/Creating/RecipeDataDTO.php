<?php

namespace App\DTOs\Creating;

use App\DTOs\DTO;

class RecipeDataDTO implements DTO
{

    public function __construct(
        public readonly string $title,
        public readonly string $description,
        public readonly string $difficulty,
    ) {
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'difficulty' => $this->difficulty
        ];
    }
}
