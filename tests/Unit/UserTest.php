<?php

namespace Tests\Unit;

use App\Models\Favorites;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{

    use RefreshDatabase;

    public function test_a_user_has_many_favorites()
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->for($user)->create();
        $favorite = Favorites::factory()->for($user)->for($recipe)->create();

        $this->assertDatabaseCount(User::class, 1);
        $this->assertDatabaseCount(Recipe::class, 1);
        $this->assertDatabaseCount(Favorites::class, 1);
        $this->assertEquals($user->favorites()->select('recipe_id')->first()->recipe_id, $favorite->recipe_id);
    }

    public function test_add_favorite()
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->forUser()->create();

        Favorites::create(['user_id' => $user->id, 'recipe_id' => $recipe->id]);

        $this->assertDatabaseCount(User::class, 2);
        $this->assertDatabaseCount(Recipe::class, 1);
        $this->assertDatabaseCount(Favorites::class, 1);
    }
}
