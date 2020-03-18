<?php

declare(strict_types=1);


namespace Support\Parser;

use Log;
use App;
use Exception;
use Support\Discovers\Identificadores\ClasseType;

class ParseModelClass extends ParseClass
{
    public $instanceClass = false;

    public function __construct($classOrReflectionClass)
    {
        parent::__construct($classOrReflectionClass);
        $this->instanceClass = static::returnInstanceForClass($classOrReflectionClass);

    }

    public function getData($indice)
    {
        $array = $this->toArray();
        return $array[$indice];
    }


    public function toArray()
    {
        // // @debug
        // dd(
        //     $this->instanceClass->getTable(), // Ex: persons
        //     $this->instanceClass->getMutatedAttributes(), // Ex: 
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

            return [

                'table' => $this->instanceClass->getTable(), // Ex: persons
                'getMutatedAttributes' => $this->instanceClass->getMutatedAttributes(), // Ex: 
                'fillable' => $this->instanceClass->getFillable(), // Ex: 
                'dates' => $this->instanceClass->getDates(), // Ex: 
                'createdAtColumn' => $this->instanceClass->getCreatedAtColumn(), // Ex: created_at
                'getUpdatedAtColumn' => $this->instanceClass->getUpdatedAtColumn(), // Ex: updated_at
                'getVisible' => $this->instanceClass->getVisible(), // Ex: []
                'getGuarded' => $this->instanceClass->getGuarded(), // Ex: 
                'getKeyName' => $this->instanceClass->getKeyName(), // Ex: code
                'getKeyType' => $this->instanceClass->getKeyType(), // ^ "string"
                'getIncrementing' => $this->instanceClass->getIncrementing(), // false or true
                'getForeignKey' => $this->instanceClass->getForeignKey(), // Ex: person_code

            ];
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


    /**
     * 
     */
    public function getNamespace()
    {
        $namespaceWithoutModels = explode("Models\\", $this->modelClass);
        return join(array_slice(explode("\\", $namespaceWithoutModels[1]), 0, -1), "\\");
    }

}
