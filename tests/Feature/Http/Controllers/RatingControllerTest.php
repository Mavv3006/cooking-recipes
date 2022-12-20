<?php

namespace Http\Controllers;

use App\Models\Rating;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RatingControllerTest extends TestCase
{
    use RefreshDatabase;

    protected Recipe $recipe;
    protected User $user;

    public function test_store()
    {
        $response = $this
            ->actingAs($this->user)
            ->post(route('ratings.create'), ['stars' => 4, 'recipe_id' => $this->recipe->id]);

        $response->assertRedirect('/');
        $this->assertEquals(4, Rating::where('user_id', $this->user->id)->first()->stars);
    }

    public function test_update_existing_rating()
    {
        $this->actingAs($this->user);
        $this->post(route('ratings.create'), ['stars' => 4, 'recipe_id' => $this->recipe->id]);

        $response = $this->post(route('ratings.create'), ['stars' => 2, 'recipe_id' => $this->recipe->id]);

        $response->assertRedirect('/');
        $this->assertEquals(2, Rating::where('user_id', $this->user->id)->first()->stars);
    }

    public function test_delete()
    {
        $this->actingAs($this->user);
        $this->post(route('ratings.create'), ['stars' => 4, 'recipe_id' => $this->recipe->id]);

        $response = $this->delete(route('ratings.delete', ['recipe' => $this->recipe->id]));

        $response->assertRedirect(route('recipes.show', ['recipe' => $this->recipe->id]));
        $this->assertCount(0, Rating::where('user_id', $this->user->id)->get());
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->recipe = Recipe::factory()->for($this->user)->create();
    }
}
