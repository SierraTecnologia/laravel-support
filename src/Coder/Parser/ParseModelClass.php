<?php

declare(strict_types=1);


namespace Support\Coder\Parser;

use Log;
use App;
use Exception;

class ParseModelClass extends ParseClass
{
    public static function getPrimaryKey($class)
    {
        return App::make($class)->getKeyName();
    }



    
    /**
     * Helpers
     */
    public static function getTableName($class)
    {
        return (static::returnModelForClass($class))->getTable();
    }

    public static function returnModelForClass($class)
    {
        Log::warning($class);

        if (!class_exists($class)) {
            throw new Exception('Class não encontrada no ModelService' . $class);
        }

        return new $class;
    }
    
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
