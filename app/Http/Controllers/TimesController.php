<?php

namespace App\Http\Controllers;

use App\Models\Times;
use App\Models\TimesUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        $time = Times::create($validator->validated());

        return back();
    }

    public function destroy(Times $time)
    {

    }
}
