<?php

namespace LearnosityQti\Processors\Learnosity\In;

use LearnosityQti\Exceptions\MappingException;
use LearnosityQti\Services\LogService;
use LearnosityQti\Utils\Log\Logger;

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
                LogService::log('Invalid JSON. Required key ' . $parameterName . ' does not exists');
                throw new MappingException('Invalid JSON. Required key ' . $parameterName . ' does not exists');
            }
        }
        $class = $clazz->newInstanceArgs($parameters);
        self::populateClassFields($class, array_diff_key($json, $parameters));
        return $class;
    }

    private static function buildField(\ReflectionParameter $parameter, $data)
    {
        // If parameter turns out to be an Object then recurse
        // if (!empty($parameter->getClass())) {
        if ($parameter->getType() && !$parameter->getType()->isBuiltin()) {
            return self::build(
                $parameter->getType()->getName(),
                $data
            );
        }
        // If parameter is type of array, then check whether it is an array of Object
        if ($parameter->getType() && $parameter->getType()->getName() === 'array') {
            $possibleObjectClassName = $parameter->getDeclaringClass()->getName() . '_' . $parameter->getName() . '_item';
            if (class_exists($possibleObjectClassName)) {
                if (in_array(null, $data, true)) {
                    // Remove `NULL` array elements if they somehow exist
                    // This avoids a fatal error, but it's likely the validation will fail.
                    LogService::log("Invalid JSON, `NULL` array elements found. They have been removed but validation may fail.");
                    Logger::error("Invalid JSON, `NULL` array elements found. They have been removed but validation may fail.");
                    $data = array_filter($data, function ($value) {
                        return !is_null($value);
                    });
                }
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
                LogService::log("Ignoring attribute '$key'. Invalid key", 'verbose');
                continue;
            }
            if ($value === null) {
                LogService::log("Ignoring attribute '$key'. Invalid key", 'verbose');
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
