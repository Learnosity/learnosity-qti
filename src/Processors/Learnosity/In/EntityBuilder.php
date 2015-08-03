<?php

namespace Learnosity\Processors\Learnosity\In;

use Learnosity\Exceptions\MappingException;
use Learnosity\Services\LogService;

class EntityBuilder
{
    public static function build($className, array $json)
    {
        $clazz = new \ReflectionClass($className);
        $parameters = [];
        foreach ($clazz->getConstructor()->getParameters() as $parameter) {
            $parameterName = $parameter->getName();
            if (isset($json[$parameterName])) {
                $parameters[$parameterName] = self::buildField($parameter, $json[$parameterName]);
            } else {
                throw new MappingException(
                    'Invalid JSON. Required key ' . $parameterName . ' does not exists',
                    MappingException::CRITICAL
                );
            }
        }
        $class = $clazz->newInstanceArgs($parameters);
        self::populateClassFields($class, array_diff_key($json, $parameters));
        return $class;
    }

    private static function buildField(\ReflectionParameter $parameter, $data)
    {
        // If parameter turns out to be an Object then recurse
        if (!empty($parameter->getClass())) {
            return self::build(
                $parameter->getClass()->getName(),
                $data
            );
        }
        // If parameter is type of array, then check whether it is an array of Object
        if ($parameter->isArray()) {
            $possibleObjectClassName = $parameter->getDeclaringClass()->getName() . '_' . $parameter->getName() . '_item';
            if (class_exists($possibleObjectClassName)) {
                return array_map(function ($values) use ($possibleObjectClassName) {
                    return self::build($possibleObjectClassName, $values);
                }, $data);
            }
        }
        return $data;
    }

    private static function populateClassFields($class, $values)
    {
        // And, set values magically using setter methods
        foreach ($values as $key => $value) {
            if (!method_exists($class, "set_$key")) {
                LogService::log("Ignoring attribute '$key'. Invalid key");
                continue;
            }
            if (empty($value)) {
                LogService::log("Ignoring attribute '$key'. Invalid key");
                continue;
            }
            $setter = new \ReflectionMethod($class, "set_$key");
            $parameters = [];
            foreach ($setter->getParameters() as $parameter) {
                $parameterName = $parameter->getName();
                $parameters[$parameterName] = self::buildField($parameter, $values[$parameterName]);
            }
            $setter->invokeArgs($class, $parameters);
        }
        return $class;
    }
}
