<?php

namespace LearnosityQti\Utils\Log;

use Exception;

class Logger
{
    private static $isOpen = false;
    private static $logFacility = LOG_LOCAL0;
    private static $logLevel = LOG_ERR;
    private static $logName;
    private static $LOG_FACILITY = [
        "local0" => LOG_LOCAL0,
        "local1" => LOG_LOCAL1,
        "local2" => LOG_LOCAL2,
        "local3" => LOG_LOCAL3,
        "local4" => LOG_LOCAL4,
        "local5" => LOG_LOCAL5,
        "local6" => LOG_LOCAL6,
        "local7" => LOG_LOCAL7
    ];
    private static $LOG_LEVEL = [
        "alert"    => LOG_ALERT,
        "critical" => LOG_CRIT,
        "error"    => LOG_ERR,
        "warning"  => LOG_WARNING,
        "info"     => LOG_INFO,
        "debug"    => LOG_DEBUG
    ];
    protected static $LOG_PREFIX = [
        LOG_ALERT   => '[ALERT] ',
        LOG_CRIT    => '[CRITICAL] ',
        LOG_ERR     => '[ERROR] ',
        LOG_WARNING => '[WARNING] ',
        LOG_INFO    => '[INFO] ',
        LOG_DEBUG   => '[DEBUG] '
    ];
    public static function setFacility($facility)
    {
        self::$logFacility = self::$LOG_FACILITY[$facility];
    }
    public static function setLevel($level)
    {
        self::$logLevel = self::$LOG_LEVEL[$level];
    }
    public static function setName($name)
    {
        self::$logName = $name;
    }
    /**
     * Action must be taken immediately
     *
     * Example: Entire website down, database unavailable, etc.
     * This should trigger the SMS alerts and wake you up.
     */
    public static function alert($msg)
    {
        self::log(LOG_ALERT, $msg);
    }
    /**
     * Critical conditions
     *
     * Example: Application component unavailable, unexpected exception.
     */
    public static function critical($msg)
    {
        self::log(LOG_CRIT, $msg);
    }
    /**
     * Runtime errors
     */
    public static function error($msg)
    {
        self::log(LOG_ERR, $msg);
    }
    /**
     * Exceptional occurrences that are not errors
     *
     * Examples: Use of deprecated APIs, poor use of an API,
     * undesirable things that are not necessarily wrong.
     */
    public static function warning($msg)
    {
        self::log(LOG_WARNING, $msg);
    }
    /**
     * Interesting events
     *
     * Examples: User logs in, SQL logs.
     */
    public static function info($msg)
    {
        self::log(LOG_INFO, $msg);
    }
    /**
     * Detailed debug information
     */
    public static function debug($msg)
    {
        self::log(LOG_DEBUG, $msg);
    }
    private static function openlog()
    {
        $facility = self::$logFacility;
        if (!openlog(self::$logName, LOG_PID | LOG_PERROR, $facility)) {
            throw new Exception('Can\'t open syslog for logName ' . $logName);
        }
    }
    protected static function log($level, $msg)
    {
        if (!self::$isOpen) {
            self::openlog();
            self::$isOpen = true;
        }
        if ($level <= self::$logLevel) {
            syslog($level, self::$LOG_PREFIX[$level] . $msg);
        }
    }
}
