<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * getPrivateMethod
     *
     * @param string $className
     * @param string $methodName
     * @return    ReflectionMethod
     * @throws ReflectionException
     * @author    Joe Sexton <joe@webtipblog.com>
     * @see https://www.webtipblog.com/unit-testing-private-methods-and-properties-with-phpunit/
     */
    public function getPrivateMethod(string $className, string $methodName): ReflectionMethod
    {
        $reflector = new ReflectionClass($className);
        $method = $reflector->getMethod($methodName);
        $method->setAccessible(true);

        return $method;
    }
}
