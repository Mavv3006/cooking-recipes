<?php

namespace App\Http\Controllers;

use App\DTOs\Creating\RecipeRequestWrapperDTO;
use App\Models\Recipe;
use App\Models\Times;
use App\Services\RecipeExtractingService;
use App\Services\RecipeIngredientService;
use App\Services\RecipeRequestParsingService;
use App\Services\RecipeService;
use App\Services\RecipeStepService;
use App\Services\RecipeTimeService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
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
    protected RecipeExtractingService $extractingService;

    public function __construct(
        RecipeService $recipeService,
        RecipeStepService $recipeStepService,
        RecipeIngredientService $recipeIngredientService,
        RecipeTimeService $recipeTimeService,
        RecipeRequestParsingService $parsingService,
        RecipeExtractingService $extractingService
    ) {
        $this->recipeService = $recipeService;
        $this->stepService = $recipeStepService;
        $this->ingredientService = $recipeIngredientService;
        $this->timeService = $recipeTimeService;
        $this->parsingService = $parsingService;
        $this->extractingService = $extractingService;
    }

    public function index(): Response
    {
        $this->authorize('viewAny', Recipe::class);
        $recipes = Recipe::with(['user' => fn($query) => $query->select('id', 'name')])
            ->select('id', 'title', 'description', 'difficulty', 'user_id')
            ->get();
        return Inertia::render('Recipes/Index', ['recipes' => $recipes]);
    }

    public function create(): Response
    {
        $this->authorize('create', Recipe::class);
        $times = Times::select('id', 'name')->get();
        $uoms = $this->extractingService->getTimeUnitOfMeasures();
        return Inertia::render('Recipes/Create', ['times' => $times, 'uoms' => $uoms]);
    }

    public function store(Request $request): Application|RedirectResponse|Redirector
    {
        $this->authorize('create', Recipe::class);
        $data = $this->validateRecipeParameters($request);

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
        $this->authorize('view', $recipe);
        $props = $this->extractingService->getRecipeDTO($recipe);

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
        $this->authorize('update', $recipe);
        $props = $this->extractingService->getRecipeDTO($recipe);

        return Inertia::render('Recipes/Edit', $props->toArray());
    }

    public function update(Request $request, Recipe $recipe): RedirectResponse
    {
        $this->authorize('update', $recipe);
        $data = $this->validateRecipeParameters($request);

        DB::beginTransaction();
        $this->recipeService->update($recipe, $data->recipe);
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
        $this->authorize('delete', $recipe);
        $recipe->delete();
        Log::info('deleted recipe', ['recipe' => $recipe->id]);
        return redirect()->route('recipes.index');
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

//        Log::debug('Content of request:', (array)$request->getContent());
//        if ($validator->fails()) {
//            Log::error("Validation failed:", (array)$validator->errors());
//        }
        $validatedData = $validator->validated();
        return $this->parsingService->extractDataIntoDto($validatedData);
    }
}
