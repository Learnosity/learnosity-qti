<?php

namespace Learnosity\Utils;

class ArrayUtil
{
    /**
     * @ref http://stackoverflow.com/questions/8567082/how-to-generate-in-php-all-combinations-of-items-in-multiple-arrays
     */
    public static function combinations(array $arrays, $i = 0)
    {
        if (!isset($arrays[$i])) {
            return [];
        }
        if ($i == count($arrays) - 1) {
            return $arrays[$i];
        }

        // get combinations from subsequent arrays
        $tmp = ArrayUtil::combinations($arrays, $i + 1);

        $result = [];

        // concat each array from tmp with each element from $arrays[$i]
        foreach ($arrays[$i] as $v) {
            foreach ($tmp as $t) {
                $result[] = is_array($t) ?
                    array_merge([$v], $t) :
                    [$v, $t];
            }
        }

        return $result;
    }

    public static function mutateResponses(array $responses)
    {
        if (count($responses) <= 1) {
            return array_values($responses[0]);
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
