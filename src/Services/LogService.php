<?php

namespace LearnosityQti\Services;

class LogService
{
    private static array $messages = [];

    public static function log($message): void
    {
        self::$messages[] = $message;
    }

    public static function flush(): array
    {
        $result = self::$messages;
        self::$messages = [];

        return $result;
    }

    public static function read(): array
    {
        return self::$messages;
    }
}
