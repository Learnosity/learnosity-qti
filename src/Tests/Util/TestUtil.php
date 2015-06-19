<?php

namespace Learnosity\Tests\Util;

use ReflectionClass;

class TestUtil
{

    public static function getMethod($methodName, $className)
    {
        $class = new ReflectionClass($className);
        $method = $class->getMethod($methodName);
        $method->setAccessible(true);
        return $method;
    }
}