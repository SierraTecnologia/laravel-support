<?php

declare(strict_types=1);


namespace Support\Coder\Parser;

use Log;
use App;
use Exception;
use Support\Coder\Discovers\Identificadores\ClasseType;

class ParseModelClass extends ParseClass
{
    public static function getPrimaryKey($class)
    {
        return App::make($class)->getKeyName();
    }

    public static function isModelClass($class)
    {
        return ClasseType::fastExecute($class, 'typeIs', 'model');
    }

    
    /**
     * Helpers
     */
    public static function getTableName($class)
    {
        if (!self::isModelClass($class)) {
            return false;
        }

        return (static::returnInstanceForClass($class))->getTable();
    }
}
