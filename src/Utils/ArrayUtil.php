<?php

namespace Learnosity\Utils;

class ArrayUtil
{
    public static function combinations(array $array)
    {
        // Initialize by adding the empty set
        $results = array(array());
        foreach ($array as $element) {
            foreach ($results as $combination) {
                array_push($results, array_merge(array($element), $combination));
            }
        }
        return $results;
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

    /**
     * Only Support following structure for now
     * [
     *   ['a'=>1],
     *   ['b'=>2]
     * ]
     * @param array $target
     * @return array
     */
    public static function arrayKeysMulti(array $target)
    {
        $keys = [];
        foreach ($target as $value) {
            $keyList = array_keys($value);
            foreach ($keyList as $v) {
                $keys[] = $v;
            }
        }
        return $keys;
    }

    /**
     * Only Support following structure for now
     * [
     *   ['a'=>1],
     *   ['b'=>2]
     * ]
     * @param array $target
     * @return array
     */
    public static function arrayValsMulti(array $target)
    {
        $keys = [];
        foreach ($target as $value) {
            $keyList = array_values($value);
            foreach ($keyList as $v) {
                $keys[] = $v;
            }
        }
        return $keys;
    }
}
