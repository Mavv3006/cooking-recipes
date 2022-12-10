<?php

namespace Tests\Unit;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FactoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_create_recipes()
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->for($user)->create();

        $this->assertDatabaseCount('recipes', 1);
        $this->assertDatabaseCount('users', 1);
        $this->assertTrue($user->id === $recipe->user_id);
    }
}
