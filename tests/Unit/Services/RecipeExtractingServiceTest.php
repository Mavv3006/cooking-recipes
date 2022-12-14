<?php

namespace Services;

use App\DTOs\Extracting\RatingsDTO;
use App\DTOs\Extracting\RecipeDTO;
use App\Models\Comment;
use App\Models\Favorites;
use App\Models\Ingredient;
use App\Models\Rating;
use App\Models\Recipe;
use App\Models\RecipeSteps;
use App\Models\RecipeTimes;
use App\Models\Times;
use App\Models\TimesUnit;
use App\Models\User;
use App\Services\RecipeExtractingService;
use Database\Seeders\TimesUnitSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class RecipeExtractingServiceTest extends TestCase
{
    use RefreshDatabase;

    protected RecipeExtractingService $service;

    public function testTimeUnitOfMeasures()
    {
        TimesUnit::factory()->create(['short' => 'h', 'long' => 'Stunde']);

        $uom = $this->service->getTimeUnitOfMeasures();

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $uom);
        $this->assertDatabaseCount((new TimesUnit())->getTable(), 1);
        $this->assertCount(1, $uom);
        $first = $uom->first();
        $this->assertEquals('h', $first->short);
        $this->assertEquals('Stunde', $first->long);
        $this->assertEquals(1, $first->id);
    }

    public function testIngredients()
    {
        $recipe = Recipe::factory()
            ->for(User::factory()->create())
            ->hasAttached(
                Ingredient::factory()->count(3),
                ['quantity' => 1, 'created_at' => now(), 'updated_at' => now()]
            )->create();

        $ingredients = $this->service->getIngredientsOfRecipe($recipe);

        $this->assertCount(3, $ingredients);
        $first = $ingredients->first();
        $this->assertTrue(property_exists($first, 'quantity'));
        $this->assertTrue(property_exists($first, 'uom'));
        $this->assertTrue(property_exists($first, 'name'));
    }

    public function testSteps()
    {
        $recipe = Recipe::factory()
            ->for(User::factory()->create())
            ->has(RecipeSteps::factory()->count(4))
            ->create();

        $steps = $this->service->getStepsOfRecipe($recipe);

        $this->assertCount(4, $steps);
        $first = $steps->first();
        $this->assertNotNull($first->description);
        $this->assertIsString($first->description);
    }

    public function testComments()
    {
        $recipe = Recipe::factory()->for(User::factory()->create())->create();
        Comment::factory()
            ->sequence(fn($sequence) => ['user_id' => User::all()->first()->id])
            ->create(['recipe_id' => $recipe->id]);

        $comment = $this->service->getCommentsOfRecipe($recipe)->first();

        $this->assertNotNull($comment->comment);
        $this->assertNotNull($comment->created_at);
        $this->assertNotNull($comment->updated_at);
        $this->assertNotNull($comment->user_id);
        $this->assertNotNull($comment->id);
    }

    public function testTimes()
    {
        $this->seed(TimesUnitSeeder::class);
        Times::factory()->count(5)->create();
        $recipe = Recipe::factory()->for(User::factory()->create())->create();
        $times = Times::all()->random(2);
        RecipeTimes::factory()
            ->count(2)
            ->sequence(fn($sequence) => ['times_id' => $times[$sequence->index]])
            ->sequence(fn($sequence) => ['times_unit_id' => TimesUnit::all()->random(1)->first()->id])
            ->create(['recipe_id' => $recipe->id]);

        $serviceTimes = $this->service->getTimesOfRecipe($recipe);

        $this->assertCount(2, $serviceTimes);
    }

    public function testRatings()
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->for($user)->create();
        Rating::factory()->for($user)->for($recipe)->create(['stars' => 2]);

        $ratings = $this->service->getRatingsOfRecipe($recipe);

        $this->assertInstanceOf(RatingsDTO::class, $ratings);
        $this->assertEquals(1, $ratings->count);
        $this->assertEquals(2, $ratings->average);
    }

    public function testRatingsMultipleUser()
    {
        $recipe = Recipe::factory()->for(User::factory()->create())->create();
        Rating::factory()
            ->count(4)
            ->for($recipe)
            ->sequence(fn($sequence) => ['user_id' => User::factory()->create()->id])
            ->sequence(fn($sequence) => ['stars' => [2, 2, 4, 4][$sequence->index]])
            ->create();

        $ratings = $this->service->getRatingsOfRecipe($recipe);

        $this->assertInstanceOf(RatingsDTO::class, $ratings);
        $this->assertEquals(4, $ratings->count);
        $this->assertEquals(3, $ratings->average);
    }

    public function testFavorite()
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->for(User::factory()->create())->create();
        Favorites::factory()->for($user)->for($recipe)->create();

        $this->actingAs($user);
        $isFavorite = $this->service->whetherTheRecipeIsAFavoriteForTheLoggedInUser($recipe);

        $this->assertTrue($isFavorite);
        $this->assertCount(1, Favorites::all());
    }

    public function testRecipeDTO()
    {
        $this->seed(TimesUnitSeeder::class);
        $user = User::factory()->create();
        $recipe = Recipe::factory()
            ->for($user)
            ->has(RecipeSteps::factory()->count(4))
            ->hasAttached(
                Ingredient::factory()->count(3),
                ['quantity' => 1, 'created_at' => now(), 'updated_at' => now()]
            )
            ->create();
        Rating::factory()
            ->count(4)
            ->for($recipe)
            ->sequence(fn($sequence) => ['user_id' => User::factory()->create()->id])
            ->sequence(fn($sequence) => ['stars' => [2, 2, 4, 4][$sequence->index]])
            ->create();
        Favorites::factory()->for($user)->for($recipe)->create();
        Times::factory()->count(5)->create();
        $times = Times::all()->random(2);
        RecipeTimes::factory()
            ->count(2)
            ->sequence(fn($sequence) => ['times_id' => $times[$sequence->index]])
            ->sequence(fn($sequence) => ['times_unit_id' => TimesUnit::all()->random(1)->first()->id])
            ->create(['recipe_id' => $recipe->id]);
        Comment::factory()
            ->sequence(fn($sequence) => ['user_id' => User::all()->first()->id])
            ->create(['recipe_id' => $recipe->id]);

        $dto = $this->service->getRecipeDTO($recipe);

        $this->assertInstanceOf(RecipeDTO::class, $dto);
        $this->assertNotNull($dto->ingredients);
        $this->assertNotNull($dto->steps);
        $this->assertNotNull($dto->comments);
        $this->assertNotNull($dto->times);
        $this->assertNotNull($dto->ratings);
        $this->assertNotNull($dto->isFavorite);
        $this->assertNotNull($dto->recipe);
        $this->assertNotNull($dto->timeUnitOfMeasures);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new RecipeExtractingService();
    }
}
