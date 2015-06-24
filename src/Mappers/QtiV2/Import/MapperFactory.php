<?php
/**
 * Created by PhpStorm.
 * User: frankan
 * Date: 24/06/2015
 * Time: 1:59 PM
 */

namespace Learnosity\Mappers\QtiV2\Import;


class MapperFactory
{

    const MAPPER_TYPE_MERGED = 'merged';
    const MAPPER_TYPE_STD = 'std';

    public function getMapper($className, array $params, $type = self::MAPPER_TYPE_STD)
    {
        $mapperClass = null;
        switch ($type) {
            case self::MAPPER_TYPE_MERGED:
                $mapperClass = 'Learnosity\Mappers\QtiV2\Import\MergedInteractions\\Merged' . $className;
                break;
            case self::MAPPER_TYPE_STD:
            default:
                $mapperClass = 'Learnosity\Mappers\QtiV2\Import\Interactions\\' . $className;
                break;
        }

        $reflectionClass = new \ReflectionClass($mapperClass);
        return $reflectionClass->newInstanceArgs($params);
    }
}