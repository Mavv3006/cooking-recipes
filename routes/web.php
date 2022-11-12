<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\TimesController;
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

Route::prefix('comments')
    ->middleware(['auth:sanctum', config('jetstream.auth_session')])
    ->group(function () {
        Route::post('', [CommentController::class, 'store'])
            ->name('comment.create');
        Route::match(['put', 'patch'], '/{comment}', [CommentController::class, 'update'])
            ->name('comment.update');
        Route::delete('/{comment}', [CommentController::class, 'destroy'])
            ->name('comment.delete');
    });

Route::prefix('ratings')
    ->middleware(['auth:sanctum', config('jetstream.auth_session')])
    ->group(function () {
        Route::post('', [RatingController::class, 'store'])
            ->name('ratings.create');
        Route::delete('/{rating}', [RatingController::class, 'destroy'])
            ->name('ratings.delete');
    });

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

Route::prefix('times')->group(function () {
    Route::get('', [TimesController::class, 'index'])->name('times.index');
});


Route::get('user/profile/favorites', [FavoritesController::class, 'index'])
    ->middleware(['auth:sanctum', config('jetstream.auth_session')])
    ->name('user.favorites');
