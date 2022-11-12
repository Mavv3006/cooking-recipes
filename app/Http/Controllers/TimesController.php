<?php

namespace App\Http\Controllers;

use App\Models\Times;
use App\Models\TimesUnit;
use Inertia\Inertia;

class TimesController extends Controller
{
    public function index()
    {
        // available times
        $times = Times::select('id', 'name')->get();

        // available time units
        $timeUnits = TimesUnit::select('id', 'short', 'long')->get();

        $props = ['times' => $times, 'timeUnits' => $timeUnits];
        return Inertia::render('Times/Index', $props);
    }
}
