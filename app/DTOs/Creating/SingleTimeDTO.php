<?php

namespace App\DTOs\Creating;

use App\DTOs\DTO;
use App\Models\RecipeTimes;
use App\Models\TimesUnit;

class SingleTimeDTO extends DTO
{
    /**
     * @param int $id The identifier for the referenced Time object
     * @param int $uom_id The identifier for the referenced Unit of Measure object for the time
     * @param float $duration The duration of the time (e.g. 20 -> with unit of measure: 20 min)
     * @see TimesUnit
     *
     * @see RecipeTimes
     */
    public function __construct(
        public readonly int $id,
        public readonly int $uom_id,
        public readonly float $duration,
    ) {
    }
}
