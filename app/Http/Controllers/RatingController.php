<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Recipe;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

        $recipe_id = $data['recipe_id'];
        $stars = $data['stars'];
        $has_rated = sizeof(Auth::user()->whereRelation('ratings', 'recipe_id', $recipe_id)->get()) > 0;
        Log::debug($has_rated ? 'true' : 'false');

        if ($has_rated) {
            Auth::user()->ratings()->updateExistingPivot($recipe_id, ['stars' => $stars]);
        } else {
            Auth::user()->ratings()->attach($recipe_id, ['stars' => $stars]);
        }
        Log::info('Attached user ' . Auth::id() . ' with recipe ' . $recipe_id . ' with ' . $stars . ' stars');


        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Recipe $recipe
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(Recipe $recipe): RedirectResponse
    {
        $rating = Rating::where('user_id', Auth::id())
            ->where('recipe_id', $recipe->id)
            ->first();
        $this->authorize('delete', $rating);

        Log::info('Deleting rating ' . $rating->id);
        Auth::user()->ratings()->detach($recipe->id);

        return redirect()->route('recipes.show', ['recipe' => $recipe->id]);
    }
}
