<?php

namespace LearnosityQti\Utils;

class AssumptionHandler
{
    private static $messages = [];

    public static function log($message)
    {
        self::$messages[] = $message;
    }

    public static function flush()
    {
        $result = self::$messages;
        self::$messages = [];
        return $result;
    }

    public static function read()
    {
        return self::$messages;
    }
}
