<?php

declare(strict_types=1);


namespace Support\Discovers\Code;

class ParseClass
{
    
    public static function getFileName($class)
    {
        return (new \ReflectionClass($class))->getFileName();
    }

    /**
     * Gets the class name.
     * @return string
     */
    public static function getClassName($class)
    {
        return strtolower(array_slice(explode('\\', $class), -1, 1)[0]);
    }
}
