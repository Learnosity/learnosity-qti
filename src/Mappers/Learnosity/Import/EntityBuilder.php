<?php

namespace Learnosity\Mappers\Learnosity\Import;

use Learnosity\Exceptions\MappingException;

class EntityBuilder
{
    /**
     * @param       $className
     * @param array $json
     *
     * @return object
     * @throws MappingException
     */
    public static function build($className, array $json)
    {
        $clazz = new \ReflectionClass($className);
        $parameters = [];
        foreach ($clazz->getConstructor()->getParameters() as $parameter) {
            $parameterName = $parameter->getName();
            if (isset($json[$parameterName])) {
                $parameters[$parameterName] = ($parameter->getClass()) ? self::build($parameter->getClass()->getName(),
                    $json[$parameterName]) : $json[$parameterName];
            } else {
                throw new MappingException('Invalid JSON. Required key ' . $parameter->getName() . ' does not exists',
                    MappingException::CRITICAL);
            }
        }
        $class = $clazz->newInstanceArgs($parameters);

        self::populateClassFields($class, array_diff_key($json, $parameters));

        return $class;
    }

    private static function populateClassFields($class, $values)
    {
        // And, set values magically using setter methods
        foreach ($values as $key => $value) {
            if (method_exists($class, "set_$key")) {
                $setter = new \ReflectionMethod($class, "set_$key");
                $parameters = [];
                foreach ($setter->getParameters() as $parameter) {
                    $parameterName = $parameter->getName();
                    $parameters[$parameterName] = ($parameter->getClass()) ? self::build($parameter->getClass()->getName(), $values[$parameterName]) :
                        $values[$parameterName];
                }
                $setter->invokeArgs($class, $parameters);
            } else {
                // TODO: Store this somewhere to be returned
                echo "Ignoring attribute '$key'. Unable to map to Learnosity entity" . PHP_EOL;
            }
        }
        return $class;
    }
} 
