<?php

namespace Services;

use App\DTOs\Creating\RecipeTimeDTO;
use App\DTOs\Creating\SingleTimeDTO;
use App\Models\Recipe;
use App\Models\RecipeTimes;
use App\Models\Times;
use App\Models\TimesUnit;
use App\Models\User;
use App\Services\RecipeTimeService;
use Database\Seeders\TimesUnitSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipeTimeServiceTest extends TestCase
{
    use RefreshDatabase;

    protected RecipeTimeService $service;

    public function testCreate()
    {
        (new TimesUnitSeeder())->run();
        $recipe = Recipe::factory()->for(User::factory())->create();
        $dto = new RecipeTimeDTO([
            new SingleTimeDTO(
                Times::factory()
                    ->create()
                    ->id,
                TimesUnit::all()
                    ->random(1)
                    ->first()
                    ->id, 23.5
            ),
        ]);

        $this->service->create($recipe, $dto);

        $this->assertDatabaseCount((new RecipeTimes())->getTable(), 1);
    }

    public function testCreateWithEmptyArray()
    {
        (new TimesUnitSeeder())->run();
        $recipe = Recipe::factory()->for(User::factory())->create();
        $dto = new RecipeTimeDTO([]);

        $this->service->create($recipe, $dto);

        $this->assertDatabaseCount((new RecipeTimes())->getTable(), 0);
    }

    public function testUpdate()
    {
        (new TimesUnitSeeder())->run();
        $recipe = Recipe::factory()->for(User::factory())->create();
        $time_id = Times::factory()
            ->create()
            ->id;
        $dto = new RecipeTimeDTO([
            new SingleTimeDTO(
                $time_id,
                TimesUnit::all()
                    ->random(1)
                    ->first()
                    ->id,
                23.5
            ),
        ]);
        $this->service->create($recipe, $dto);

        $newDto = new RecipeTimeDTO([
            new SingleTimeDTO(
                $time_id,
                TimesUnit::all()
                    ->random(1)
                    ->first()
                    ->id,
                25
            ),
        ]);
        $this->service->update($recipe, $newDto);

        $this->assertDatabaseCount((new RecipeTimes())->getTable(), 1);
        $this->assertEquals(25, RecipeTimes::all()->first()->duration);
    }

    public function testUpdateDeletingExistingTime()
    {
        (new TimesUnitSeeder())->run();
        $recipe = Recipe::factory()->for(User::factory())->create();
        $time_id = Times::factory()
            ->create()
            ->id;
        $dto = new RecipeTimeDTO([
            new SingleTimeDTO(
                $time_id,
                TimesUnit::all()
                    ->random(1)
                    ->first()
                    ->id,
                23.5
            ),
        ]);
        $this->service->create($recipe, $dto);

        $newDto = new RecipeTimeDTO([
            new SingleTimeDTO(
                $time_id,
                TimesUnit::all()
                    ->random(1)
                    ->first()
                    ->id,
                0
            ),
        ]);
        $this->service->update($recipe, $newDto);

        $this->assertDatabaseCount((new RecipeTimes())->getTable(), 0);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new RecipeTimeService();
    }
}
