<?php

namespace Services;

use App\DTOs\Creating\RecipeDataDTO;
use App\DTOs\Creating\RecipeIngredientDTO;
use App\DTOs\Creating\RecipeRequestWrapperDTO;
use App\DTOs\Creating\RecipeStepDTO;
use App\DTOs\Creating\RecipeTimeDTO;
use App\Services\RecipeRequestParsingService;
use Tests\TestCase;

class RecipeRequestParsingServiceTest extends TestCase
{
    protected RecipeRequestParsingService $parsingService;
    protected string $className;

    public function testExtractingSteps()
    {
        $data = [
            ['description' => 'hi', 'id' => 123]
        ];

        $method = $this->getPrivateMethod($this->className, 'extractRecipeStepDTO');
        $result = $method->invokeArgs($this->parsingService, array($data));

        $this->assertInstanceOf(RecipeStepDTO::class, $result);
    }

    public function testExtractingStepsWithoutId()
    {
        $data = [
            ['description' => 'hi',]
        ];

        $method = $this->getPrivateMethod($this->className, 'extractRecipeStepDTO');
        $result = $method->invokeArgs($this->parsingService, array($data));

        $this->assertInstanceOf(RecipeStepDTO::class, $result);
    }

    public function testExtractingStepsWithAndWithoutId()
    {
        $data = [
            ['description' => 'hi',],
            ['description' => 'hi', 'id' => 123]
        ];

        $method = $this->getPrivateMethod($this->className, 'extractRecipeStepDTO');
        $result = $method->invokeArgs($this->parsingService, array($data));

        $this->assertInstanceOf(RecipeStepDTO::class, $result);
        $this->assertEquals('hi', $result->elements[0]->description);
        $this->assertEquals('hi', $result->elements[1]->description);
        $this->assertEquals(123, $result->elements[1]->id);
    }

    public function testExtractingTimes()
    {
        $data = [
            ['id' => 1, 'uom_id' => 123, 'duration' => 12.3234]
        ];

        $method = $this->getPrivateMethod($this->className, 'extractRecipeTimeDTO');
        $result = $method->invokeArgs($this->parsingService, array($data));

        $this->assertNotNull($result);
    }

    public function testExtractEverything()
    {
        $data = [
            "title" => "Emilia Kunz B.Sc.",
            "description" => "At minima deserunt possimus iste. Nostrum sed voluptatum voluptatem eveniet quia sint ut. Voluptatibus esse iusto perferendis sed laboriosam sunt autem.",
            "steps" => [
                [
                    "description" => "Nihil et nesciunt cupiditate omnis. Magni voluptatem sit ea et est reprehenderit. Dicta quae delectus et nihil odio quam occaecati."
                ],
                [
                    "description" => "Repudiandae ut aut sint voluptatem rerum. Magni recusandae sunt explicabo est. Dolor neque fuga ea maxime aut impedit. Vero maxime dignissimos numquam qui ipsum. Illo quidem et officiis."
                ],
                [
                    "description" => "Id nobis dolor autem odit excepturi. Quia enim ipsa pariatur fuga. Veritatis sed voluptatem maxime consequuntur voluptates accusantium. Eum quasi facilis rerum officia est."
                ],
                [
                    "description" => "Rerum dolorum maiores fugit cum qui quo aut. Eveniet impedit vel fuga aut velit. Consequatur culpa officiis odit quisquam nisi molestiae. Aperiam in doloribus rerum quis ullam omnis explicabo nihil."
                ]
            ],
            "ingredients" => [
                ["description" => "1 ea oditcumque"],
                ["description" => "1 ml nonsint"],
                ["description" => "1 g idvoluptates"]
            ],
            "difficulty" => "normal",
            "times" => [
                ["id" => 1, "uom_id" => 1, "duration" => 89.42],
                ["id" => 3, "uom_id" => 3, "duration" => 17.09],
                ["id" => 4, "uom_id" => 1, "duration" => 34.92]
            ]
        ];

        $result = $this->parsingService->extractDataIntoDto($data);

        $this->assertInstanceOf(RecipeRequestWrapperDTO::class, $result);
        $this->assertInstanceOf(RecipeStepDTO::class, $result->steps);
        $this->assertInstanceOf(RecipeTimeDTO::class, $result->times);
        $this->assertInstanceOf(RecipeIngredientDTO::class, $result->ingredients);
        $this->assertInstanceOf(RecipeDataDTO::class, $result->recipe);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->parsingService = new RecipeRequestParsingService();
        $this->className = RecipeRequestParsingService::class;
    }
}
