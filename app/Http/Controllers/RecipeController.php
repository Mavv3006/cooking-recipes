<?php

namespace App\Http\Controllers;

use App\DTOs\Creating\RecipeRequestWrapperDTO;
use App\DTOs\Extracting\RatingsDTO;
use App\DTOs\Extracting\RecipeDTO;
use App\Models\Recipe;
use App\Models\Times;
use App\Models\TimesUnit;
use App\Services\RecipeIngredientService;
use App\Services\RecipeRequestParsingService;
use App\Services\RecipeService;
use App\Services\RecipeStepService;
use App\Services\RecipeTimeService;
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
    protected RecipeStepService $stepService;
    protected RecipeIngredientService $ingredientService;
    protected RecipeTimeService $timeService;
    protected RecipeRequestParsingService $parsingService;

    public function __construct(
        RecipeService $recipeService,
        RecipeStepService $recipeStepService,
        RecipeIngredientService $recipeIngredientService,
        RecipeTimeService $recipeTimeService,
        RecipeRequestParsingService $parsingService
    ) {
        $this->recipeService = $recipeService;
        $this->stepService = $recipeStepService;
        $this->ingredientService = $recipeIngredientService;
        $this->timeService = $recipeTimeService;
        $this->parsingService = $parsingService;
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
        $data = $this->validateRecipeParameters($request);
        Log::debug('validated data:', (array)$data);

        DB::beginTransaction();
        $recipe = $this->recipeService->create($request->user(), $data->recipe);
        $this->stepService->create($recipe, $data->steps);
        $this->ingredientService->create($recipe, $data->ingredients);
        $this->timeService->create($recipe, $data->times);
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
        $data = $this->validateRecipeParameters($request);
        DB::beginTransaction();
        $this->stepService->update($recipe, $data->steps);
        $this->ingredientService->update($recipe, $data->ingredients);
        $this->timeService->update($recipe, $data->times);
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

    private function validateRecipeParameters(Request $request): RecipeRequestWrapperDTO
    {
        $rules = [
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
            'steps.*.id' => 'integer|min:0',
            'difficulty' => ['required', Rule::in(['easy', 'normal', 'hard'])],
            'times' => 'array:id,uom_id,duration',
            'times.*.id' => 'integer|min:0',
            'times.*.uom_id' => 'integer|min:0',
            'times.*.duration' => 'numeric|min:0',
        ];
        $validator = Validator::make($request->all(), $rules);

        Log::debug('Content of request:', (array)$request->getContent());
        if ($validator->fails()) {
            Log::error("Validation failed:", (array)$validator->errors());
        }
        $validatedData = $validator->validated();
        return $this->parsingService->extractDataIntoDto($validatedData);
    }
}
