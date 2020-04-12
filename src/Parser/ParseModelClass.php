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
        //     $this->instanceClass->getFillable(), // $this->modelClass = $modelClassx: getTableName
        //      */

        //     $this->instanceClass->getKey() // Ex: null
        // );
        if (!$this->isModelClass()) {
            return false;
        }

        return [

            'table' => $this->getTableName(), // Ex: persons
            'getMutatedAttributes' => $this->instanceClass->getMutatedAttributes(), // Ex: 
            'fillable' => $this->instanceClass->getFillable(), // Ex: 
            'dates' => $this->instanceClass->getDates(), // Ex: 
            'createdAtColumn' => $this->instanceClass->getCreatedAtColumn(), // Ex: created_at
            'getUpdatedAtColumn' => $this->instanceClass->getUpdatedAtColumn(), // Ex: updated_at
            'getVisible' => $this->instanceClass->getVisible(), // Ex: []
            'getGuarded' => $this->instanceClass->getGuarded(), // Ex: 
            'getKeyName' => $this->getPrimaryKey(), // Ex: code
            'getKeyType' => $this->instanceClass->getKeyType(), // ^ "string"
            'getIncrementing' => $this->instanceClass->getIncrementing(), // false or true
            'getForeignKey' => $this->instanceClass->getForeignKey(), // Ex: person_code

        ];
    }




    public function getPrimaryKey()
    {
        return $this->instanceClass->getKeyName();
    }

    public function isModelClass()
    {
        return $this->typeIs('model');
    }

    
    /**
     * Helpers
     */
    public function getTableName()
    {
        return $this->instanceClass->getTable();
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
