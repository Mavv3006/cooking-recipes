<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class RatingController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Rating::class);

        $validator = Validator::make($request->all(), [
            'stars' => ['required', Rule::in(range(1, 5))],
            'recipe_id' => 'required|numeric|min:1',
        ]);

        $data = $validator->validate();

        $rating_data = [
            'stars' => $data['stars'],
            'recipe_id' => $data['recipe_id'],
            'user_id' => Auth::id()
        ];

        Log::debug(json_encode($rating_data), ['class' => get_class($this)]);
        $rating = Rating::create($rating_data);
        Log::info('Created rating ' . $rating);

        return back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Rating $rating
     * @return RedirectResponse
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function update(Request $request, Rating $rating): RedirectResponse
    {
        $this->authorize('update', $rating);

        $validator = Validator::make($request->all(), [
            'stars' => ['required', Rule::in(range(1, 5))],
        ]);

        $data = $validator->validate();

        Log::info('Updating rating ' . $rating->id);
        $rating->update($data);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Rating $rating
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(Rating $rating): RedirectResponse
    {
        $this->authorize('delete', $rating);

        $recipe_id = $rating->recipe_id;
        Log::info('Deleting rating ' . $rating->id);
        $rating->delete();

        return redirect()->route('recipes.show', ['recipe' => $recipe_id]);
    }
}
