<?php

namespace App\DTOs;

use Illuminate\Contracts\Support\Arrayable;

abstract class DTO implements Arrayable
{
    public function toArray(): array
    {
        return (array)$this;
    }
}
