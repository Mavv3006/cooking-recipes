<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\TimesController;
use Illuminate\Support\Facades\Route;

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
    return redirect(\route('recipes.index'));
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
        Route::delete('/{recipe}', [RatingController::class, 'destroy'])
            ->name('ratings.delete');
    });

Route::resource('recipes', RecipeController::class)
    ->only('create', 'store', 'destroy', 'edit', 'update')
    ->middleware(['auth:sanctum', config('jetstream.auth_session')]);

Route::prefix('recipes')->group(function () {
    Route::get('', [RecipeController::class, 'index'])
        ->name('recipes.index');
    Route::get('/{recipe}', [RecipeController::class, 'show'])->name('recipes.show');
    Route::post('/{recipe}/favorite', [FavoritesController::class, 'store'])
        ->middleware(['auth:sanctum', config('jetstream.auth_session')])
        ->name('favorites.store');
    Route::post('/{recipe}/image', [ImageController::class, 'store'])
        ->name('image.store');
    Route::get('/{recipe}/image', [ImageController::class, 'create'])
        ->name('image.create');
    Route::match(['put', 'patch'], '/{recipe}/image/{image}', [ImageController::class, 'update'])
        ->name('image.update');
    Route::delete('/{recipe}/image/{image}', [ImageController::class, 'destroy'])
        ->name('image.delete');
});

Route::resource('times', TimesController::class)
    ->only('index', 'store', 'destroy', 'update')
    ->names([
        'index' => 'times.index',
        'store' => 'times.store',
        'destroy' => 'times.delete',
        'update' => 'times.update'
    ])
    ->middleware(['auth:sanctum', config('jetstream.auth_session')]);

Route::get('user/profile/favorites', [FavoritesController::class, 'index'])
    ->middleware(['auth:sanctum', config('jetstream.auth_session')])
    ->name('user.favorites');
