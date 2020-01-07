<?php

declare(strict_types=1);


namespace Support\Coder\Parser;

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

    public static function returnInstanceForClass($class)
    {

        if (!class_exists($class)) {
            Log::warning('[Support] Code Parser -> Class não encontrada no ModelService' . $class);
            throw new Exception('Class não encontrada no ModelService' . $class);
        }

        // return new $class;
        return with(new $class);
    }
}
