<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeIngredient;
use App\Models\RecipeTimes;
use App\Models\Times;
use App\Models\TimesUnit;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        $recipes = Recipe::with(['user' => fn($query) => $query->select('id', 'name')])
            ->select('id', 'title', 'description', 'difficulty', 'user_id')
            ->get();
        return Inertia::render('Recipes/Index', ['recipes' => $recipes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(): Response
    {
        $times = Times::select('id', 'name')->get();
        $uoms = TimesUnit::select('id', 'short', 'long')->get();
        return Inertia::render('Recipes/Create', ['times' => $times, 'uoms' => $uoms]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
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
            'times' => 'array:id,uom_id,duration',
            'times.*.id' => 'integer|min:0',
            'times.*.uom_id' => 'integer|min:0',
            'times.*.duration' => 'numeric|min:0',
        ]);

        DB::beginTransaction();
        $recipe = $this->createRecipe($request, $validator);
        $this->createRecipeSteps($validator, $recipe);
        $this->createRecipeIngredients($validator, $recipe);
        if (sizeof($validator->validated()['times']) > 0) {
            $this->createRecipeTimes($validator, $recipe);
        }
        DB::commit();

        return redirect()->route('recipes.show', ['recipe' => $recipe->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  Recipe  $recipe
     * @return Response
     */
    public function show(Recipe $recipe): Response
    {
        // Ingredients for this recipe
        $ingredients = DB::table('recipes')
            ->join('recipe_ingredients', 'recipes.id', '=', 'recipe_ingredients.recipe_id')
            ->join('ingredients', 'recipe_ingredients.ingredient_id', '=', 'ingredients.id')
            ->where('recipes.id', $recipe->id)
            ->select(['quantity', 'uom', 'name'])
            ->get();

        // Steps in this recipe
        $steps = $recipe
            ->steps()
            ->orderBy('id')
            ->select('description')
            ->get();

        // Whether the recipe is a favorite for the logged-in user
        $is_favorite = null;
        $is_logged_in = Auth::check();
        if ($is_logged_in) {
            $favorite = Auth::user()
                ->favorites()
                ->where('id', $recipe->id)
                ->first();
            $is_favorite = !($favorite == null);
        }

        // available comments for this recipe
        $comments = $recipe->comments()
            ->with(['user' => fn($query) => $query->select('id', 'name')])
            ->orderBy('created_at')
            ->select('comment', 'created_at', 'updated_at', 'user_id', 'id')
            ->get();

        // available ratings
        $ratings = $recipe->ratings()
            ->get()
            ->pipe(fn($collection) => [
                'count' => $collection->count('stars'),
                'avg' => $collection->avg('stars')
            ]);

        // times
        $times = $recipe->recipeTimes()->with([
            'time' => fn($query) => $query->select('id', 'name'),
            'timesUnit' => fn($query) => $query->select('id', 'short', 'long')
        ])->get();

        // return object
        $props = [
            'recipe' => $recipe,
            'ingredients' => $ingredients,
            'steps' => $steps,
            'user' => $recipe->user()->first(),
            'is_favorite' => $is_favorite,
            'is_logged_in' => $is_logged_in,
            'comments' => $comments,
            'ratings' => $ratings,
            'times' => $times,
        ];

        return Inertia::render('Recipes/Show', $props);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Recipe  $recipe
     * @return Response
     */
    public function edit(Recipe $recipe)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Recipe  $recipe
     * @return Response
     */
    public function update(Request $request, Recipe $recipe)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Recipe  $recipe
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
        $recipe = $request->user()->recipes()->create($validator->validated());
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

    public function createRecipeTimes(
        \Illuminate\Contracts\Validation\Validator|\Illuminate\Validation\Validator $validator,
        mixed $recipe
    ): void {
        Log::debug('Zeiten anlegen');
        $request_times = $validator->safe()->only('times')['times'];
        Log::debug('times: ' . json_encode($request_times));
        foreach ($request_times as $time) {
            $recipe_time = RecipeTimes::create([
                'recipe_id' => $recipe->id,
                'times_id' => $time['id'],
                'times_unit_id' => $time['uom_id'],
                'duration' => $time['duration']
            ]);
            Log::debug('Recipe time for recipe ' . $recipe_time->recipe_id . ' and time ' . $recipe_time->times_id . ' with duration ' . $recipe_time->duration . ' created.');
        }
        Log::info('all recipe times created');
    }
}
