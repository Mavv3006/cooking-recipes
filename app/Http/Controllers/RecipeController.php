<?php

namespace App\Http\Controllers;

use App\DTOs\Creating\RecipeDataDTO;
use App\DTOs\Creating\RecipeService;
use App\DTOs\Extracting\RatingsDTO;
use App\DTOs\Extracting\RecipeDTO;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeIngredient;
use App\Models\RecipeTimes;
use App\Models\Times;
use App\Models\TimesUnit;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Collection;
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
    protected RecipeService $recipeService;

    public function __construct(RecipeService $recipeService)
    {
        $this->recipeService = $recipeService;
    }

    public function index(): Response
    {
        $recipes = Recipe::with(['user' => fn($query) => $query->select('id', 'name')])
            ->select('id', 'title', 'description', 'difficulty', 'user_id')
            ->get();
        return Inertia::render('Recipes/Index', ['recipes' => $recipes]);
    }

    public function create(): Response
    {
        $times = Times::select('id', 'name')->get();
        $uoms = $this->getTimeUnitOfMeasures();
        return Inertia::render('Recipes/Create', ['times' => $times, 'uoms' => $uoms]);
    }

    public function store(Request $request): Application|RedirectResponse|Redirector
    {
        $validator = $this->validateRecipeParameters($request);
        $data = $validator->validated();
        $recipeDataDto = new RecipeDataDTO($data['title'], $data['description'], $data['difficulty']);
        DB::beginTransaction();
        $recipe = $this->recipeService->create($request->user(), $recipeDataDto);
        $this->createRecipeSteps($validator, $recipe);
        $this->createRecipeIngredients($validator, $recipe);
        if (sizeof($validator->validated()['times']) > 0) {
            $this->createRecipeTimes($validator, $recipe);
        }
        DB::commit();

        return redirect()->route('recipes.show', ['recipe' => $recipe->id]);
    }

    public function show(Recipe $recipe): Response
    {
        $props = $this->getRecipeDTO($recipe);

        return Inertia::render('Recipes/Show', $props->toArray());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Recipe $recipe
     * @return Response
     */
    public function edit(Recipe $recipe)
    {
        $props = $this->getRecipeDTO($recipe);

        return Inertia::render('Recipes/Edit', $props->toArray());
    }

    public function update(Request $request, Recipe $recipe): RedirectResponse
    {
        $validator = $this->validateRecipeParameters($request);
        DB::beginTransaction();
        $this->updateRecipeSteps($validator, $recipe);
        $this->updateRecipeIngredients($validator, $recipe);
        if (sizeof($validator->validated()['times']) > 0) {
            $this->updateRecipeTimes($validator, $recipe);
        }
        DB::commit();

        return redirect()->route('recipes.show', ['recipe' => $recipe->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Recipe $recipe
     * @return RedirectResponse
     */
    public function destroy(Recipe $recipe): RedirectResponse
    {
        $recipe->delete();
        Log::info('deleted recipe', ['recipe' => $recipe->id]);
        return redirect()->route('recipes.index');
    }

    private function createRecipeSteps(
        \Illuminate\Contracts\Validation\Validator|\Illuminate\Validation\Validator $validator,
        $recipe
    ): void {
        Log::debug('Create steps');
        $steps = $validator->safe()->only(['steps'])['steps'];
        Log::debug('steps: ' . json_encode($steps));
        $recipe->steps()->createMany($steps);
        Log::info('All recipe ' . sizeof($steps) . ' steps created');
    }

    private function updateRecipeSteps(
        \Illuminate\Contracts\Validation\Validator|\Illuminate\Validation\Validator $validator,
        Recipe $recipe
    ): void {
        Log::debug('Update steps');
        $steps = $validator->safe()->only(['steps'])['steps'];
        Log::debug('steps: ' . json_encode($steps));
        $recipe->steps()->delete();
        $recipe->steps()->createMany($steps);
        Log::info('All recipe ' . sizeof($steps) . ' steps updated');
    }

    private function createRecipeIngredients(
        \Illuminate\Contracts\Validation\Validator|\Illuminate\Validation\Validator $validator,
        Recipe $recipe
    ): void {
        Log::debug('Create ingredients');
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
            Log::debug(
                'Recipe Ingredient for recipe ' . $created_recipe_ingredient->recipe_id . ' and ingredient ' . $created_recipe_ingredient->ingredient_id . ' created.'
            );
        }
        Log::info('all recipe ingredients created');
    }

    private function updateRecipeIngredients(
        \Illuminate\Contracts\Validation\Validator|\Illuminate\Validation\Validator $validator,
        Recipe $recipe
    ): void {
        Log::debug('Update ingredients');
        $request_ingredients = $validator->safe()->only('ingredients')['ingredients'];
        Log::debug('ingredients: ' . json_encode($request_ingredients));
        foreach ($request_ingredients as $request_ingredient) {
            $individual_components = preg_split('/\s/', $request_ingredient['description']);
            Log::debug(json_encode($individual_components));
            $ingredient = Ingredient::firstOrCreate([
                'name' => $individual_components[2],
                'uom' => $individual_components[1]
            ]);
            RecipeIngredient::where('recipe_id', $recipe->id)
                ->where('ingredient_id', $ingredient->id)
                ->update(['quantity' => $individual_components[0]]);
            Log::debug(
                'Recipe Ingredient for recipe ' . $recipe->id . ' and ingredient ' . $ingredient->id . ' updated.'
            );
        }
        Log::info('all recipe ingredients created');
    }

    public function createRecipeTimes(
        \Illuminate\Contracts\Validation\Validator|\Illuminate\Validation\Validator $validator,
        Recipe $recipe
    ): void {
        Log::debug('Create times');
        $request_times = $validator->safe()->only('times')['times'];
        Log::debug('times: ' . json_encode($request_times));
        foreach ($request_times as $time) {
            $recipe_time = RecipeTimes::create([
                'recipe_id' => $recipe->id,
                'times_id' => $time['id'],
                'times_unit_id' => $time['uom_id'],
                'duration' => $time['duration']
            ]);
            Log::debug(
                'Recipe time for recipe ' . $recipe_time->recipe_id . ' and time ' . $recipe_time->times_id . ' with duration ' . $recipe_time->duration . ' created.'
            );
        }
        Log::info('all recipe times created');
    }

    public function updateRecipeTimes(
        \Illuminate\Contracts\Validation\Validator|\Illuminate\Validation\Validator $validator,
        Recipe $recipe
    ): void {
        Log::debug('Update times');
        $request_times = $validator->safe()->only('times')['times'];
        Log::debug('times: ' . json_encode($request_times));
        foreach ($request_times as $time) {
            RecipeTimes::where('recipe_id', $recipe->id)
                ->where('times_id', $time['id'])
                ->where('times_unit_id', $time['uom_id'])
                ->update(['duration' => $time['duration']]);
            Log::debug(
                'Recipe time for recipe ' . $recipe->id . ' and time ' . $time['id'] . ' with duration ' . $time['duration'] . ' updated.'
            );
        }
        Log::info('all recipe times updated');
    }

    private function getStepsOfRecipe(Recipe $recipe): Collection
    {
        return $recipe
            ->steps()
            ->orderBy('id')
            ->select('description')
            ->get();
    }

    private function getIngredientsOfRecipe(Recipe $recipe): \Illuminate\Support\Collection
    {
        return DB::table('recipes')
            ->join('recipe_ingredients', 'recipes.id', '=', 'recipe_ingredients.recipe_id')
            ->join('ingredients', 'recipe_ingredients.ingredient_id', '=', 'ingredients.id')
            ->where('recipes.id', $recipe->id)
            ->select(['quantity', 'uom', 'name'])
            ->get();
    }

    private function whetherTheRecipeIsAFavoriteForTheLoggedInUser(Recipe $recipe): bool
    {
        $is_favorite = null;
        $is_logged_in = Auth::check();
        if ($is_logged_in) {
            $favorite = Auth::user()
                ->favorites()
                ->where('id', $recipe->id)
                ->first();
            $is_favorite = !($favorite == null);
        }
        return $is_favorite ?? false;
    }

    private function getCommentsOfRecipe(Recipe $recipe): Collection
    {
        return $recipe->comments()
            ->with(['user' => fn($query) => $query->select('id', 'name')])
            ->orderBy('created_at')
            ->select('comment', 'created_at', 'updated_at', 'user_id', 'id')
            ->get();
    }

    private function getRatingsOfRecipe(Recipe $recipe): RatingsDTO
    {
        return $recipe->ratings()
            ->get()
            ->pipe(fn($collection) => new RatingsDTO($collection->count('stars'), $collection->avg('stars')));
    }

    private function getTimesOfRecipe(Recipe $recipe): Collection
    {
        return $recipe->recipeTimes()
            ->with([
                'time' => fn($query) => $query->select('id', 'name'),
                'timesUnit' => fn($query) => $query->select('id', 'short', 'long')
            ])
            ->get()
            ->filter(function ($value) {
                return $value['time'] != null;
            });
    }

    /// Todo: refactor into service container (also move called methods)
    private function getRecipeDTO(Recipe $recipe): RecipeDTO
    {
        return new RecipeDTO(
            $this->getIngredientsOfRecipe($recipe),
            $this->getStepsOfRecipe($recipe),
            $this->getCommentsOfRecipe($recipe),
            $this->getTimesOfRecipe($recipe),
            $this->getRatingsOfRecipe($recipe),
            $this->whetherTheRecipeIsAFavoriteForTheLoggedInUser($recipe),
            $recipe,
            $this->getTimeUnitOfMeasures()
        );
    }

    private function getTimeUnitOfMeasures(): Collection
    {
        return TimesUnit::select('id', 'short', 'long')->get();
    }

    private function validateRecipeParameters(Request $request
    ): \Illuminate\Validation\Validator|\Illuminate\Contracts\Validation\Validator {
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

        Log::debug('Content of request:');
        Log::debug($request->getContent());
        Log::debug(" ");
        if ($validator->fails()) {
            Log::error("Validation failed:");
            Log::error($validator->errors());
            Log::error(" ");
        }
        return $validator;
    }
}
