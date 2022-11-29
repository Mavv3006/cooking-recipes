<?php

namespace Database\Seeders;

use App\Models\TimesUnit;
use Illuminate\Database\Seeder;

class TimesUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $timeUnits = [
            ['short' => 'h', 'long' => 'Stunde',],
            ['short' => 'min', 'long' => 'Minute',],
            ['short' => 's', 'long' => 'Sekunde',],
        ];

        foreach ($timeUnits as $timeUnit) {
            TimesUnit::factory()->create($timeUnit);
        }
    }
}
