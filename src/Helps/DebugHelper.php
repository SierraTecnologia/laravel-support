<?php 

namespace Support\Helps;

class DebugHelper
{
    public static function debug($message)
    {
        self::printMessage('[Debug] '.$message);
    }

    public static function info($message)
    {
        self::printMessage('[Info] '.$message);
    }

    public static function warning($message)
    {
        self::printMessage('[Info] '.$message);
    }

    private static function printMessage($message)
    {
        echo $message."\n";
    }
}