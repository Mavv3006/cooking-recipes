<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Recipe;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * Will come later. This function returns all the images a particular
     * user has saved for any recipe. The list returned by this function
     * should be accessible from the user profile page.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create(Recipe $recipe): \Inertia\Response
    {
        return Inertia::render('Images/Create', ['recipe' => $recipe->id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Recipe $recipe
     * @return RedirectResponse
     */
    public function store(Request $request, Recipe $recipe): RedirectResponse
    {
        $path = $request->file('image')->storePublicly('images', 'public');

        $image = new Image(['path' => $path, 'recipe_id' => $recipe->id]);

        Auth::user()->images()->save($image);
        Log::info(
            'Uploaded a new image for user ' . Auth::id() . ' and recipe ' . $recipe->id . ': ' . $path
        );

        return redirect(route('recipes.show', ['recipe' => $recipe->id]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Image $image
     * @return Response
     */
    public function update(Request $request, Image $image)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Image $image
     * @return Response
     */
    public function destroy(Image $image)
    {
        //
    }
}
