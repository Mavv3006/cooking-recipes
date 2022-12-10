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

    protected RecipeStepService $service;

    public function testCreate()
    {
        $recipe = Recipe::factory()->for(User::factory())->create();
        $dto = new RecipeStepDTO(['description' => 'test']);

        $this->service->create($recipe, $dto);

        $this->assertDatabaseCount((new RecipeSteps())->getTable(), 1);
    }

    public function testUpdate()
    {
        $recipe = Recipe::factory()->for(User::factory())->create();
        $dto = new RecipeStepDTO(['description' => 'test']);
        $this->service->create($recipe, $dto);

        $this->service->update($recipe, new RecipeStepDTO(['description' => 'bla bla']));

        $this->assertDatabaseCount((new RecipeSteps())->getTable(), 1);
        $this->assertEquals('bla bla', RecipeSteps::all()->first()->description);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new RecipeStepService();
    }
}
