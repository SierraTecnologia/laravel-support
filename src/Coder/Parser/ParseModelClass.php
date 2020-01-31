<?php

declare(strict_types=1);


namespace Support\Coder\Parser;

use Log;
use App;
use Exception;
use Support\Coder\Discovers\Identificadores\ClasseType;

class ParseModelClass extends ParseClass
{
    public $reflectionClass = false;

    public function __construct($classOrReflectionClass)
    {
        parent::__construct($classOrReflectionClass);
        $this->instanceClass = static::returnInstanceForClass($classOrReflectionClass);
        // // @debug
        // dd(
        //     $this->instanceClass->getTable(), // Ex: persons
        //     $this->instanceClass->getMutatedAttributes(), // Ex: 
        //     // $this->instanceClass->getMutatorMethods(), // Ex: 
        //     $this->instanceClass->getFillable(), // Ex: 
        //     $this->instanceClass->getDates(), // Ex: 
        //     $this->instanceClass->getCreatedAtColumn(), // Ex: created_at
        //     $this->instanceClass->getUpdatedAtColumn(), // Ex: updated_at
        //     $this->instanceClass->getVisible(), // Ex: []
        //     $this->instanceClass->getGuarded(), // Ex: 
        //     $this->instanceClass->getKeyName(), // Ex: code
        //     $this->instanceClass->getKeyType(), // ^ "string"
        //     $this->instanceClass->getIncrementing(), // false or true
        //     $this->instanceClass->getForeignKey(), // Ex: person_code


        //     /**
        //      * Para Registro
        //      */

        //     $this->instanceClass->getKey() // Ex: null
        // );


    }




    public static function getPrimaryKey($class)
    {
        return (static::returnInstanceForClass($class))->getKeyName();
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

        return (self::returnInstanceForClass($class))->getTable();
    }
}
