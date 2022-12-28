<?php

namespace App\Services;

use App\DTOs\Extracting\RatingsDTO;
use App\DTOs\Extracting\RecipeDTO;
use App\Models\Recipe;
use App\Models\TimesUnit;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RecipeExtractingService
{
    public function getRecipeDTO(Recipe $recipe): RecipeDTO
    {
        return new RecipeDTO(
            $this->getIngredientsOfRecipe($recipe),
            $this->getStepsOfRecipe($recipe),
            $this->getCommentsOfRecipe($recipe),
            $this->getTimesOfRecipe($recipe),
            $this->getRatingsOfRecipe($recipe),
            $this->whetherTheRecipeIsAFavoriteForTheLoggedInUser($recipe),
            $recipe,
            $this->getTimeUnitOfMeasures(),
            $this->getImagesFor($recipe),
            $this->getAuthorOfRecipe($recipe),
        );
    }

    public function getIngredientsOfRecipe(Recipe $recipe): \Illuminate\Support\Collection
    {
        return DB::table('recipes')
            ->join('recipe_ingredients', 'recipes.id', '=', 'recipe_ingredients.recipe_id')
            ->join('ingredients', 'recipe_ingredients.ingredient_id', '=', 'ingredients.id')
            ->where('recipes.id', $recipe->id)
            ->select(['quantity', 'uom', 'name'])
            ->get();
    }

    public function getStepsOfRecipe(Recipe $recipe): Collection
    {
        return $recipe
            ->steps()
            ->orderBy('id')
            ->select('description')
            ->get();
    }

    public function getCommentsOfRecipe(Recipe $recipe): Collection
    {
        return $recipe->comments()
            ->with(['user' => fn($query) => $query->select('id', 'name')])
            ->orderBy('created_at')
            ->select('comment', 'created_at', 'updated_at', 'user_id', 'id')
            ->get();
    }

    public function getTimesOfRecipe(Recipe $recipe): Collection
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

    public function getRatingsOfRecipe(Recipe $recipe): RatingsDTO
    {
        return $recipe->ratings()
            ->get()
            ->pipe(fn($collection) => new RatingsDTO($collection->count('stars') ?? 0, $collection->avg('stars') ?? 0));
    }

    public function whetherTheRecipeIsAFavoriteForTheLoggedInUser(Recipe $recipe): bool
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

    public function getTimeUnitOfMeasures(): Collection
    {
        return TimesUnit::select('id', 'short', 'long')->get();
    }

    public function getImagesFor(Recipe $recipe): Collection
    {
        return $recipe
            ->images()
            ->with(['user' => fn($query) => $query->select('id', 'name')])
            ->select('path', 'user_id')
            ->get();
    }

    public function getAuthorOfRecipe(Recipe $recipe): Model
    {
        return $recipe->user()->getResults();
    }
}
