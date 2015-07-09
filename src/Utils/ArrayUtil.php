<?php

namespace Learnosity\Utils;

use Learnosity\Processors\Learnosity\In\ValidationBuilder\ValidResponse;

class ArrayUtil
{
    public static function combinations(array $array)
    {
        // Initialize by adding the empty set
        $results = [[]];
        foreach ($array as $element) {
            foreach ($results as $combination) {
                array_push($results, array_merge([$element], $combination));
            }
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
        $combinations = array_map(function ($combination) {
            $score = 0;
            $value = [];
            /** @var ValidResponse $response */
            foreach ($combination as $response) {
                $score += $response->getScore(); // We handle score simply by summing them
                $value = array_merge($value, $response->getValue());
            }
            return new ValidResponse($score, $value);
        }, $result);
        return $combinations;
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

    public static function mutateResponses(array $responses)
    {
        if (count($responses) <= 1) {
            return array_values(isset($responses[0]) ? $responses[0] : []);
        } else {
            $res = [];
            $first = array_shift($responses);
            $remaining = self::mutateResponses($responses);
            foreach ($first as $fKey => $f) {
                foreach ($remaining as $rKey => $r) {
                    if (!is_array($f)) {
                        $f = [$f];
                    }
                    if (!is_array($r)) {
                        $r = [$r];
                    }
                    $res[] = array_merge($f, $r);
                }
            }
            return $res;
        }
    }
}
