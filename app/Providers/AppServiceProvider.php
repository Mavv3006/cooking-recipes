<?php

namespace App\Providers;

use App\Services\RecipeExtractingService;
use App\Services\RecipeIngredientService;
use App\Services\RecipeRequestParsingService;
use App\Services\RecipeService;
use App\Services\RecipeStepService;
use App\Services\RecipeTimeService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(RecipeService::class, RecipeService::class);
        $this->app->bind(RecipeStepService::class, RecipeStepService::class);
        $this->app->bind(RecipeIngredientService::class, RecipeIngredientService::class);
        $this->app->bind(RecipeTimeService::class, RecipeTimeService::class);
        $this->app->bind(RecipeRequestParsingService::class, RecipeRequestParsingService::class);
        $this->app->bind(RecipeExtractingService::class, RecipeExtractingService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
