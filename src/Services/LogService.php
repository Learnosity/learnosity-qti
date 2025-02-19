<?php

namespace LearnosityQti\Services;

class LogService
{
    const LEVEL_NORMAL = 'normal';
    const LEVEL_VERBOSE = 'verbose';

    private static $messages = [];
    private static $verbosity = false; // Default to normal logging

    public static function log($message, $level = self::LEVEL_NORMAL)
    {
        self::$messages[] = [
            'message' => $message,
            'level' => $level
        ];
    }

    public static function enableVerbose()
    {
        self::$verbosity = true;
    }

    public static function disableVerbose()
    {
        self::$verbosity = false;
    }

    public static function flush()
    {
        $filteredMessages = self::getFilteredMessages();
        self::$messages = [];
        return $filteredMessages;
    }

    public static function read()
    {
        return self::getFilteredMessages();
    }

    private static function getFilteredMessages()
    {
        return array_map(
            fn($entry) => $entry['message'],
            array_filter(
                self::$messages,
                fn($entry) => self::$verbosity || $entry['level'] === self::LEVEL_NORMAL
            )
        );
    }
}
