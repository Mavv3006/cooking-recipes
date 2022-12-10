<?php

namespace Services;

use App\DTOs\Creating\RecipeStepDTO;
use App\Models\Recipe;
use App\Models\RecipeSteps;
use App\Models\User;
use App\Services\RecipeStepService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class RecipeStepServiceTest extends TestCase
{

    use RefreshDatabase;

    protected RecipeStepService $recipeStepService;

    public function testCreate()
    {
        $recipe = Recipe::factory()->for(User::factory())->create();
        $dto = new RecipeStepDTO(['description' => 'test']);

        $this->recipeStepService->create($recipe, $dto);

        $this->assertDatabaseCount((new RecipeSteps())->getTable(), 1);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->recipeStepService = new RecipeStepService();
    }
}
