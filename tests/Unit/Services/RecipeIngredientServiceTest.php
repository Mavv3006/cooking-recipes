<?php

namespace Services;

use App\DTOs\Creating\RecipeIngredientDTO;
use App\Models\Ingredient;
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

    public function testUpdate()
    {
        $recipe = Recipe::factory()->for(User::factory())->create();
        $dto = new RecipeIngredientDTO(['description' => '1 g bla_bla']);
        $this->service->create($recipe, $dto);

        $this->service->update($recipe, new RecipeIngredientDTO(['description' => '2 g hi_hi']));

        $this->assertDatabaseCount((new RecipeIngredient())->getTable(), 1);
        $this->assertEquals('2', RecipeIngredient::all()->first()->quantity);
        $ingredient = RecipeIngredient::all()->first()->ingredient()->get()->first();
        $this->assertEquals('hi_hi', $ingredient->name);
        $this->assertDatabaseCount((new Ingredient())->getTable(), 2);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new RecipeIngredientService();
    }
}
