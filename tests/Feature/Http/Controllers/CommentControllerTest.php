<?php

namespace Http\Controllers;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;

    protected Recipe $recipe;
    protected User $user;

    public function test_store()
    {
        $response = $this->post(
            route('comment.create', [
                'comment' => 'bla bla',
                'recipe_id' => $this->recipe->id
            ])
        );

        $response->assertRedirect('/');
        $this->assertCount(1, $this->user->comments()->get());
    }

    public function test_update()
    {
        $this->post(
            route('comment.create', [
                'comment' => 'bla bla',
                'recipe_id' => $this->recipe->id
            ])
        );
        $comment = $this->user->comments()->first();

        $response = $this->put('comments/' . $comment->id, ['comment' => 'hi hi']);

        $response->assertRedirect('/');
        $this->assertCount(1, $this->user->comments()->get());
        $this->assertEquals('hi hi', $this->user->comments()->first()->comment);
    }

    public function test_delete()
    {
        $this->post(
            route('comment.create', [
                'comment' => 'bla bla',
                'recipe_id' => $this->recipe->id
            ])
        );
        $comment = $this->user->comments()->first();
        $route = route('comment.delete', ['comment' => $comment->id]);

        $response = $this->delete($route);

        $this->assertCount(0, $this->user->comments()->get());
        $response->assertRedirect(route('recipes.show', ['recipe' => $this->recipe->id]));
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->recipe = Recipe::factory()->for($this->user)->create();
        $this->actingAs($this->user);
    }
}
