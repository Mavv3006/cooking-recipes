<?php

namespace Services;

use App\DTOs\Creating\RecipeStepDTO;
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
            [
                'id' => 1,
                'uom_id' => 123,
                'duration' => 12.3234
            ]
        ];

        $method = $this->getPrivateMethod($this->className, 'extractRecipeTimeDTO');
        $result = $method->invokeArgs($this->parsingService, array($data));

        $this->assertNotNull($result);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->parsingService = new RecipeRequestParsingService();
        $this->className = RecipeRequestParsingService::class;
    }
}
