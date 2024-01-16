<?php 

namespace Muleta\Helps;

class DebugHelper
{
    public static function debug($message): void
    {
        self::printMessage('[Debug] '.$message);
    }

    public static function info($message): void
    {
        self::printMessage('[Info] '.$message);
    }

    public static function warning(string $message): void
    {
        self::printMessage('[Info] '.$message);
    }

    private static function printMessage(string $message): void
    {
        echo $message."\n";
    }
}