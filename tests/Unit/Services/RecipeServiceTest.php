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

    public function testUpdate()
    {
        $user = User::factory()->create();
        $dto = new RecipeDataDTO('title', 'description', 'normal');
        $recipe = $this->recipeService->create($user, $dto);

        $this->recipeService->update($recipe, new RecipeDataDTO('title', 'test', 'hard'));

        $this->assertDatabaseCount($recipe->getTable(), 1);

        $newRecipe = Recipe::all()->first();
        $this->assertEquals('test', $newRecipe->description);
        $this->assertEquals('hard', $newRecipe->difficulty);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->recipeService = new RecipeService();
    }
}
