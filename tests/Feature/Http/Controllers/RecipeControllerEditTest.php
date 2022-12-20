<?php

namespace Http\Controllers;

use App\Models\Recipe;
use App\Models\RecipeIngredient;
use App\Models\RecipeSteps;
use App\Models\Times;
use App\Models\TimesUnit;
use App\Models\User;
use Database\Seeders\TimesUnitSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipeControllerEditTest extends TestCase
{
    use RefreshDatabase;

    public function testEditTitle()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $this->seed(TimesUnitSeeder::class);
        $this->actingAs($user);
        $uom_id = TimesUnit::find(1)->id;
        $time_id = Times::factory()->count(3)->create()->random(1)->first()->id;
        $this->post(route('recipes.store'), [
            'title' => 'test title',
            'description' => 'test description',
            'difficulty' => 'normal',
            'steps' => [
                ['description' => 'hihi'],
                ['description' => 'hihi'],
                ['description' => 'hihi']
            ],
            'ingredients' => [
                ['description' => '1 g slkdjfslkd1'],
                ['description' => '1 g slkdjfslkd2'],
                ['description' => '1 g slkdjfslkd3']
            ],
            'times' => [
                [
                    'id' => $time_id,
                    'uom_id' => $uom_id,
                    'duration' => 4
                ]
            ]
        ]);
        $data = [
            'title' => 'bla bla',
            'description' => 'test description',
            'difficulty' => 'normal',
            'steps' => [
                ['description' => 'hihi'],
                ['description' => 'hihi'],
            ],
            'ingredients' => [
                ['description' => '1 g slkdjfslkd1'],
                ['description' => '1 g slkdjfslkd2'],
            ],
            'times' => [
                [
                    'id' => $time_id,
                    'uom_id' => $uom_id,
                    'duration' => 5
                ]
            ]
        ];

        $response = $this->put('recipes/1', $data);

        $response->assertRedirect('recipes/1');
        $this->assertCount(1, Recipe::all());
        $recipe = Recipe::all()->first();
        $this->assertEquals('bla bla', $recipe->title);
        $this->assertEquals('test description', $recipe->description);
        $this->assertEquals('normal', $recipe->difficulty);
        $this->assertCount(2, RecipeSteps::all());
        $this->assertCount(2, RecipeIngredient::all());
    }
}
