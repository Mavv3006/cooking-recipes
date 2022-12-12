<?php

namespace Tests\Feature\Controllers;

use App\Models\Recipe;
use App\Models\Times;
use App\Models\TimesUnit;
use App\Models\User;
use Database\Seeders\TimesUnitSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class RecipeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_showing_recipe_list()
    {
        $this->actingAs(User::factory()->create());
        $user = User::factory()->create();
        $recipe = Recipe::factory()->for($user)->count(5)->create();

        $response = $this->get('recipes');

        $response->assertOk();
        $response->assertInertia(fn(AssertableInertia $page) => $page
            ->has('recipes', 5, fn(AssertableInertia $page) => $page
                ->whereAll([
                    'id' => $recipe[0]->id,
                    'title' => $recipe[0]->title,
                    'description' => $recipe[0]->description,
                    'difficulty' => $recipe[0]->difficulty,
                    'user_id' => $recipe[0]->user_id
                ])
                ->has('user', fn(AssertableInertia $page) => $page
                    ->whereAll([
                        'id' => $user->id,
                        'name' => $user->name
                    ])
                )
            )
        );
    }

    public function test_showing_recipe_creation_form()
    {
        $this->seed(TimesUnitSeeder::class);
        $time = Times::factory()->count(2)->create();
        $uoms = TimesUnit::all();

        $response = $this->actingAs(User::factory()->create())->get('recipes/create');

        $response->assertOk();
        $response->assertInertia(fn(AssertableInertia $page) => $page
            ->component('Recipes/Create')
            ->has('times', 2, fn(AssertableInertia $page) => $page
                ->whereAll([
                    'id' => $time[0]->id,
                    'name' => $time[0]->name
                ])
            )
            ->has('uoms', 3, fn(AssertableInertia $page) => $page
                ->whereAll([
                    'id' => $uoms[0]->id,
                    'short' => $uoms[0]->short,
                    'long' => $uoms[0]->long
                ])
            )
        );
    }
}
