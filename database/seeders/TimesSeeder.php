<?php

namespace Database\Seeders;

use App\Models\Times;
use Illuminate\Database\Seeder;

class TimesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Times::factory()->count(5)->create();
    }
}
