<?php

namespace App\Http\Controllers;

use App\Models\Favorites;
use App\Models\Recipe;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;

class FavoritesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Recipe $recipe
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function store(Recipe $recipe, Request $request): Application|RedirectResponse|Redirector
    {
        $user = $recipe->user();
        Favorites::create(['user_id' => $user->id, 'recipe_id' => $recipe->id]);

        return redirect(route('recipes.show', $recipe->id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
