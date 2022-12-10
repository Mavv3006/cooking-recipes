<?php

namespace App\Providers;

use App\Services\RecipeIngredientService;
use App\Services\RecipeService;
use App\Services\RecipeStepService;
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
