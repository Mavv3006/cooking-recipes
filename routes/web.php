<?php

use App\Http\Controllers\RecipeController;
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
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('recipes', RecipeController::class)
    ->only('index', 'show', 'create', 'store')
    ->names([
        'index' => 'recipes.all',
        'show' => 'recipes.one',
        'create' => 'recipes.create',
        'store'=>'recipes.store',
    ]);

require __DIR__ . '/auth.php';
