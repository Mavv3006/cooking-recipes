<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeIngredient;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
            'ingredients.*.description' => [
                'required',
                'regex:/.* .* .*/i',
                function ($attribute, $value, $fail) {
                    $splitted_data = preg_split('/\s/', $value);
                    if (sizeof($splitted_data) !== 3) {
                        $fail('The ' . $attribute . ' does not has the correct format.');
                    }
                },
            ],
            'steps' => 'required|array|min:1',
            'steps.*.description' => 'required|string',
            'difficulty' => ['required', Rule::in(['easy', 'normal', 'hard'])],
        ]);

        DB::beginTransaction();
        $recipe = $this->createRecipe($request, $validator);
        $this->createRecipeSteps($validator, $recipe);
        $this->createRecipeIngredients($validator, $recipe);
        DB::commit();

        return redirect(route('recipes.create'));
    }

    /**
     * Display the specified resource.
     *
     * @param Recipe $recipe
     * @return \Inertia\Response
     */
    public function show(Recipe $recipe)
    {
        Log::info('Showing recipe ' . $recipe->id);

        $ingredients = DB::table('recipes')
            ->join('recipe_ingredients', 'recipes.id', '=', 'recipe_ingredients.recipe_id')
            ->join('ingredients', 'recipe_ingredients.ingredient_id', '=', 'ingredients.id')
            ->where('recipes.id', '=', $recipe->id)
            ->select(['quantity', 'uom', 'name'])
            ->get();
        $steps = $recipe
            ->steps()
            ->orderBy('id')
            ->select('description')
            ->get();

        Log::debug('recipe: ' . json_encode($recipe));
        Log::debug('steps: ' . json_encode($steps));
        Log::debug('ingredients: ' . json_encode($ingredients));

        $props = [
            'recipe' => $recipe,
            'ingredients' => $ingredients,
            'steps' => $steps,
            'user' => $recipe->user()->first(),
        ];

        return Inertia::render('Recipes/Show', $props);
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

    private function createRecipeSteps(
        \Illuminate\Contracts\Validation\Validator|\Illuminate\Validation\Validator $validator,
        $recipe
    ): void {
        Log::debug('Zubereitungsschritte anlegen');
        $steps = $validator->safe()->only(['steps'])['steps'];
        Log::debug('steps: ' . json_encode($steps));
        $recipe->steps()->createMany($steps);
        Log::info('All recipe ' . sizeof($steps) . ' steps created');
    }

    private function createRecipe(
        Request $request,
        \Illuminate\Contracts\Validation\Validator|\Illuminate\Validation\Validator $validator
    ): mixed {
        Log::debug('Rezept anlegen');
        $recipe = $request->user()->recipes()->create($validator->validate());
        Log::info('Recipe created: ' . $recipe->id);
        return $recipe;
    }

    private function createRecipeIngredients(
        \Illuminate\Contracts\Validation\Validator|\Illuminate\Validation\Validator $validator,
        mixed $recipe
    ): void {
        Log::debug('Zutaten anlegen');
        $request_ingredients = $validator->safe()->only('ingredients')['ingredients'];
        Log::debug('ingredients: ' . json_encode($request_ingredients));
        foreach ($request_ingredients as $request_ingredient) {
            $individual_components = preg_split('/\s/', $request_ingredient['description']);
            Log::debug(json_encode($individual_components));
            $ingredient = Ingredient::firstOrCreate([
                'name' => $individual_components[2],
                'uom' => $individual_components[1]
            ]);
            $recipe_ingredient_values = [
                'quantity' => $individual_components[0],
                'recipe_id' => $recipe->id,
                'ingredient_id' => $ingredient->id
            ];
            $created_recipe_ingredient = RecipeIngredient::create($recipe_ingredient_values);
            Log::debug('Recipe Ingredient for recipe ' . $created_recipe_ingredient->recipe_id . ' and ingredient ' . $created_recipe_ingredient->ingredient_id . ' created.');
        }
        Log::info('all recipe ingredients created');
    }
}
