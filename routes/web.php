<?php

use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\RecipeController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('index');

Route::prefix('recipes')->group(function () {
    Route::get('', [RecipeController::class, 'index'])->name('recipes.index');
    Route::resource('', RecipeController::class)
        ->only('create', 'store')
        ->names(['create' => 'recipes.create', 'store' => 'recipes.store'])
        ->middleware(['auth:sanctum', config('jetstream.auth_session')]);
    Route::get('/{recipe}', [RecipeController::class, 'show'])->name('recipes.show');
    Route::post('/{recipe}/favorite', [FavoritesController::class, 'store'])
        ->middleware(['auth:sanctum', config('jetstream.auth_session')])
        ->name('favorites.store');
});

Route::get('user/profile/favorites', [FavoritesController::class, 'index'])
    ->middleware(['auth:sanctum', config('jetstream.auth_session')])
    ->name('user.favorites');
