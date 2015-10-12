<?php

namespace LearnosityQti\Utils;

use LearnosityQti\Processors\Learnosity\In\ValidationBuilder\ValidResponse;

class ArrayUtil
{
    public static function combinations(array $array, $length = null)
    {
        // Initialize by adding the empty set
        $results = [[]];
        foreach ($array as $element) {
            foreach ($results as $combination) {
                array_push($results, array_merge([$element], $combination));
            }
        }
        // Remove the first empty item
        array_shift($results);
        // Remote the items with counts larger than `maxCount`
        if ($length !== null) {
            $results = array_filter($results, function ($result) use ($length) {
                return count($result) <= $length;
            });
        }
        return $results;
    }

    /**
     * @param array $collections an array of array of ValidResponse(s) per interaction
     * @return array $responses a list of ValidResponse object combination
     */
    public static function cartesianProductForResponses(array $collections)
    {
        $result = self::cartesianProduct($collections);
        $combinations = self::combineValidResponsesWithSummedScore($result);
        return $combinations;
    }

    public static function combineValidResponsesWithSummedScore(array $collection)
    {
        // This used to generate valid response objects for `map_response`
        return array_map(function ($combination) {
            $score = 0;
            $value = [];
            /** @var ValidResponse $response */
            foreach ($combination as $response) {
                $score += $response->getScore(); // We handle score simply by summing them
                $value = array_merge($value, $response->getValue());
            }
            return new ValidResponse($score, $value);
        }, $collection);
    }

    public static function combineValidResponsesWithFixedScore(array $collection, $fixedScore)
    {
        // This used to generate valid response objects for `match_correct`
        // in which the score is always be 1
        return array_map(function ($combination) use ($fixedScore) {
            $value = [];
            /** @var ValidResponse $response */
            foreach ($combination as $response) {
                $value = array_merge($value, $response->getValue());
            }
            return new ValidResponse($fixedScore, $value);
        }, $collection);
    }

    public static function cartesianProduct(array $arrays)
    {
        $result = [];
        $sizeIn = sizeof($arrays);
        $size = $sizeIn > 0 ? 1 : 0;

        foreach ($arrays as $array) {
            $size = $size * sizeof($array);
        }
        for ($i = 0; $i < $size; $i++) {
            $result[$i] = [];
            for ($j = 0; $j < $sizeIn; $j++) {
                array_push($result[$i], current($arrays[$j]));
            }
            for ($j = ($sizeIn - 1); $j >= 0; $j--) {
                if (next($arrays[$j])) {
                    break;
                } elseif (isset ($arrays[$j])) {
                    reset($arrays[$j]);
                }
            }
        }
        return $result;
    }
}
