<?php

namespace LearnosityQti\Utils\General;

class ArrayHelper
{
    /**
     * To sort an array of associative arrays by value of a given key
     * @see http://stackoverflow.com/questions/1597736/how-to-sort-an-array-of-associative-arrays-by-value-of-a-given-key-in-php
     * @example
     * $valueKey = 'price'
     * $array = [
     *       ["type" => "fruit", "price" => 3.50],
     *       ["type" => "milk", "price" => 2.90],
     *       ["type" => "pork", "price" => 5.43]
     * ];
     *
     * returns:
     * $sortedArray = [
     *       ["type" => "fruit", "price" => 3.50],
     *       ["type" => "milk", "price" => 2.90],
     *       ["type" => "pork", "price" => 5.43]
     * ];
     *
     * @param $array
     * @param $valueKey
     * @param int $sortType
     * @return array
     */
    public static function arrayAssociativeSortByValue($array, $valueKey, $sortType = SORT_ASC)
    {
        $tempArray = [];
        foreach ($array as $key => $value) {
            $tempArray[$key] = $value[$valueKey];
        }
        array_multisort($tempArray, $sortType, $array);
        return $array;
    }
}
