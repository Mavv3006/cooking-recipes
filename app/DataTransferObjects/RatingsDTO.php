<?php

namespace App\DataTransferObjects;

class RatingsDTO
{
    public function __construct(
        public readonly int $count,
        public readonly int $average,
    ) {
    }

    public function toArray(): array
    {
        return ['average' => $this->average, 'count' => $this->count];
    }
}
