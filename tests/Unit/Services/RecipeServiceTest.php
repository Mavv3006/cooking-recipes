<?php

namespace Services;

use App\DTOs\Creating\RecipeDataDTO;
use App\Models\Recipe;
use App\Models\User;
use App\Services\RecipeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipeServiceTest extends TestCase
{
    use RefreshDatabase;

    protected RecipeService $recipeService;

    public function testCreate()
    {
        $user = User::factory()->create();
        $dto = new RecipeDataDTO('title', 'description', 'normal');

        $recipe = $this->recipeService->create($user, $dto);

        $this->assertInstanceOf(Recipe::class, $recipe);
        $this->assertDatabaseCount($recipe->getTable(), 1);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->recipeService = new RecipeService();
    }
}
