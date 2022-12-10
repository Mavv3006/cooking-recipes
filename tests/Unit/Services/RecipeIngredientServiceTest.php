<?php

namespace Services;

use App\DTOs\Creating\RecipeIngredientDTO;
use App\Models\Recipe;
use App\Models\RecipeIngredient;
use App\Models\User;
use App\Services\RecipeIngredientService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipeIngredientServiceTest extends TestCase
{
    use RefreshDatabase;

    protected RecipeIngredientService $service;

    public function testCreate()
    {
        $recipe = Recipe::factory()->for(User::factory())->create();
        $dto = new RecipeIngredientDTO(['description' => '1 g bla_bla']);

        $this->service->create($recipe, $dto);

        $this->assertDatabaseCount((new RecipeIngredient())->getTable(), 1);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new RecipeIngredientService();
    }
}
