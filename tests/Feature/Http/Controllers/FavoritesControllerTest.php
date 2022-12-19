<?php

namespace Http\Controllers;

use App\Models\Favorites;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class FavoritesControllerTest extends TestCase
{
    use RefreshDatabase;

    protected Recipe $recipe;
    protected User $user;

    public function test_add_fav()
    {
        $response = $this->post(route('favorites.store', ['recipe' => $this->recipe->id]));

        $response->assertRedirect('/');
        $this->assertCount(1, Favorites::all());
    }

    public function test_remove_fav()
    {
        $this->post(route('favorites.store', ['recipe' => $this->recipe->id]));

        $response = $this->post(route('favorites.store', ['recipe' => $this->recipe->id]));

        $response->assertRedirect('/');
        $this->assertCount(0, Favorites::all());
    }

    public function test_index()
    {
        $this->withoutExceptionHandling();
        $this->post(route('favorites.store', ['recipe' => $this->recipe->id]));

        $response = $this->get(route('user.favorites'));

        $response->assertOk();
        $response->assertInertia(fn(AssertableInertia $page) => $page
            ->component('Favorites/Index')
            ->has('favorites')
        );
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->recipe = Recipe::factory()->for($this->user)->create();
        $this->actingAs($this->user);
    }
}
