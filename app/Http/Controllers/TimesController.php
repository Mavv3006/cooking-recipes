<?php

namespace App\Http\Controllers;

use App\Models\Times;
use App\Models\TimesUnit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response;

class TimesController extends Controller
{
    public function index(): Response
    {
        // available times
        $times = Times::select('id', 'name')->get();

        // available time units
        $timeUnits = TimesUnit::select('id', 'short', 'long')->get();

        $props = ['times' => $times, 'timeUnits' => $timeUnits];
        return Inertia::render('Times/Index', $props);
    }

    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        $time = Times::create($validator->validated());
        Log::info('Created a new time', ['time' => $time]);

        return back();
    }

    public function update(Request $request, Times $time): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        $time->update($validator->validated());
        Log::info('Updated time', ['time' => $time->id]);

        return back();
    }

    public function destroy(Times $time): RedirectResponse
    {
        $time->delete();
        Log::info('Soft deleted time', ['time' => $time->id]);

        return back();
    }
}
