<?php

namespace Learnosity\Entities;

abstract class BaseEntity
{
    public function to_array()
    {
        $res = [];
        foreach (get_object_vars($this) as $name => $value) {
            if (is_object($value) && is_callable(array($value, 'to_array'))) {
                $res[$name] = $value->to_array();
            } elseif (is_array($value)) {
                foreach ($value as $v) {
                    if (is_object($v) && is_callable(array($v, 'to_array'))) {
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
