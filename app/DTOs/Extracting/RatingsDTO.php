<?php

namespace App\DTOs\Extracting;

use App\DTOs\DTO;

class RatingsDTO extends DTO
{
    public function __construct(
        public readonly int $count,
        public readonly int $average,
    ) {
    }
}
