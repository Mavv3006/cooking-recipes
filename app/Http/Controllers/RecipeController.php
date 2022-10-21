<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        $recipes = Recipe::all();
        return response()->view('recipes.index', ['recipes' => $recipes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create(): \Inertia\Response
    {
        return Inertia::render('Recipes/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Application|Redirector|RedirectResponse
     */
    public function store(Request $request): Application|RedirectResponse|Redirector
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:100',
            'description' => 'required|string|max:255',
            'ingredients' => 'required|array|min:1',
            'ingredients.*.description' => 'required',
            'steps' => 'required|array|min:1',
            'steps.*.description' => 'required|string',
            'difficulty' => ['required', Rule::in(['easy', 'normal', 'hard'])],
            'prep_time' => 'required|string|max:255',
        ]);
        return redirect(route('recipes.create'));
    }

    /**
     * Display the specified resource.
     *
     * @param Recipe $recipe
     * @return Response
     */
    public function show(Recipe $recipe)
    {
        return response()->view('recipes.show', ['recipe' => $recipe]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Recipe $recipe
     * @return Response
     */
    public function edit(Recipe $recipe)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Recipe $recipe
     * @return Response
     */
    public function update(Request $request, Recipe $recipe)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Recipe $recipe
     * @return Response
     */
    public function destroy(Recipe $recipe)
    {
        //
    }
}
