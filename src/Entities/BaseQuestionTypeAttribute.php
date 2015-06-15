<?php


namespace Learnosity\Entities;


abstract class BaseQuestionTypeAttribute
{
    public function to_array()
    {
        $res = [];
        foreach (get_object_vars($this) as $name => $value) {
            if (is_object($value) && is_callable(array($value, 'to_array'))) {
                $res[$name] = $value->to_array();
            } elseif (!is_null($value)) {
                $res[$name] = $value;
            }
        }
        return $res;
    }
}