<?php

namespace LearnosityQti\Entities;

abstract class BaseEntity
{
    public function to_array(): array
    {
        $res = [];
        foreach (get_object_vars($this) as $name => $value) {
            if (is_object($value) && is_callable([$value, 'to_array'])) {
                $res[$name] = $value->to_array();
            } elseif (is_object($value)) {
                // HACK: Provide basic support for plain objects (i.e. assoc arrays)
                foreach ((array)$value as $k => $v) {
                    if (is_object($v) && is_callable([$v, 'to_array'])) {
                        $res[$name][$k] = $v->to_array();
                    } else {
                        $res[$name][$k] = $v;
                    }
                }
            } elseif (is_array($value)) {
                foreach ($value as $v) {
                    if (is_object($v) && is_callable([$v, 'to_array'])) {
                        $res[$name][] = $v->to_array();
                    } else {
                        $res[$name][] = $v;
                    }
                }
            } elseif (!is_null($value)) {
                $res[$name] = $value;
            }
        }
        return $res;
    }
}
