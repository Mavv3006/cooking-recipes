<?php

namespace App\DTOs\Extracting;

use App\DTOs\DTO;

class RatingsDTO implements DTO
{
    public function __construct(
        public readonly int $count,
        public readonly int $average,
    ) {
    }

    public function toArray(): array
    {
        return (array)$this;
    }
}
