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

    public $table = false;
    public $getMutatedAttributes = false;
    public $fillable = false;
    public $dates = false;
    public $createdAtColumn = false;
    public $getUpdatedAtColumn = false;
    public $getVisible = false;
    public $getGuarded = false;
    public $getKeyName = false;
    public $getKeyType = false;
    public $getIncrementing = false;
    public $getForeignKey = false;




    public function __construct($classOrReflectionClass)
    {
        parent::__construct($classOrReflectionClass);
    }
    
    public function getInstanceClassForUse()
    {
        if (!$this->instanceClass) {
            try {
                $this->instanceClass = static::returnInstanceForClass($this->className);
            } catch(LogicException|ErrorException|RuntimeException|OutOfBoundsException|TypeError|ValidationException|FatalThrowableError|FatalErrorException|Exception|Throwable  $e) {
                $this->setErrors(
                    $e,
                    [
                        'model' => $this->className
                    ]
                );
            }
        }
        return $this->instanceClass;
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

        $arrayParent = parent::toArray();

        $array = [

            'table' => $this->getTableName(), // Ex: persons
            'getMutatedAttributes' => $this->getMutatedAttributes(), // Ex: 
            'fillable' => $this->getInstanceClassForUse()->getFillable(), // Ex: 
            'dates' => $this->getInstanceClassForUse()->getDates(), // Ex: 
            'createdAtColumn' => $this->getInstanceClassForUse()->getCreatedAtColumn(), // Ex: created_at
            'getUpdatedAtColumn' => $this->getInstanceClassForUse()->getUpdatedAtColumn(), // Ex: updated_at
            'getVisible' => $this->getInstanceClassForUse()->getVisible(), // Ex: []
            'getGuarded' => $this->getInstanceClassForUse()->getGuarded(), // Ex: 
            'getKeyName' => $this->getPrimaryKey(), // Ex: code
            'getKeyType' => $this->getInstanceClassForUse()->getKeyType(), // ^ "string"
            'getIncrementing' => $this->getInstanceClassForUse()->getIncrementing(), // false or true
            'getForeignKey' => $this->getInstanceClassForUse()->getForeignKey(), // Ex: person_code

        ];

        return array_merge(
            $arrayParent,
            $array
        );
    }

    public function fromArray($array)
    {
        if (!$array) {
            return false;
        }
        if (!is_array($array)) {
            dd('Debug ParseModelClass', $array);
        }
        if (isset($array['table'])) {
            $this->setTableName($array['table']);
        }
        if (isset($array['getMutatedAttributes'])) {
            $this->setMutatedAttributes($array['getMutatedAttributes']);
        }
        if (isset($array['fillable'])) {
            $this->setFillable($array['fillable']);
        }
        if (isset($array['dates'])) {
            $this->setDates($array['dates']);
        }
        if (isset($array['createdAtColumn'])) {
            $this->setCreatedAtColumn($array['createdAtColumn']);
        }
        if (isset($array['getUpdatedAtColumn'])) {
            $this->setUpdatedAtColumn($array['getUpdatedAtColumn']);
        }
        if (isset($array['getVisible'])) {
            $this->setVisible($array['getVisible']);
        }
        if (isset($array['getGuarded'])) {
            $this->setGuarded($array['getGuarded']);
        }
        if (isset($array['getKeyName'])) {
            $this->setPrimaryKey($array['getKeyName']);
        }
        if (isset($array['getKeyType'])) {
            $this->setKeyType($array['getKeyType']);
        }
        if (isset($array['getIncrementing'])) {
            $this->setIncrementing($array['getIncrementing']);
        }
        if (isset($array['getForeignKey'])) {
            $this->setForeignKey($array['getForeignKey']);
        }
        parent::fromArray($array);
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
        if ($this->table === false) {
            $this->table = $this->getInstanceClassForUse()->getTable();
        }
        return $this->table;
    }
    public function setTableName($table)
    {
        $this->table = $table;
    }
    public function getMutatedAttributes()
    {
        if ($this->getMutatedAttributes === false) {
            $this->getMutatedAttributes = $this->getInstanceClassForUse()->getMutatedAttributes();
        }
        return $this->getMutatedAttributes;
    }
    public function setMutatedAttributes($getMutatedAttributes)
    {
        $this->getMutatedAttributes = $getMutatedAttributes;
    }

















    public function getFillable()
    {
        if ($this->fillable === false) {
            $this->fillable = $this->getInstanceClassForUse()->getFillable();
        }
        return $this->fillable;
    }
    public function setFillable($fillable)
    {
        $this->fillable = $fillable;
    }
    
    
    public function getDates()
    {
        if ($this->dates === false) {
            $this->dates = $this->getInstanceClassForUse()->getDates();
        }
        return $this->dates;
    }
    public function setDates($dates)
    {
        $this->dates = $dates;
    }
    
    public function getCreatedAtColumn()
    {
        if ($this->createdAtColumn === false) {
            $this->createdAtColumn = $this->getInstanceClassForUse()->getCreatedAtColumn();
        }
        return $this->createdAtColumn;
    }
    public function setCreatedAtColumn($createdAtColumn)
    {
        $this->createdAtColumn = $createdAtColumn;
    }
    
    
    public function getUpdatedAtColumn()
    {
        if ($this->getUpdatedAtColumn === false) {
            $this->getUpdatedAtColumn = $this->getInstanceClassForUse()->getUpdatedAtColumn();
        }
        return $this->getUpdatedAtColumn;
    }
    public function setUpdatedAtColumn($getUpdatedAtColumn)
    {
        $this->getUpdatedAtColumn = $getUpdatedAtColumn;
    }
    
    
    public function getVisible()
    {
        if ($this->getVisible === false) {
            $this->getVisible = $this->getInstanceClassForUse()->getVisible();
        }
        return $this->getVisible;
    }
    public function setVisible($getVisible)
    {
        $this->getVisible = $getVisible;
    }
    
    
    public function getGuarded()
    {
        if ($this->getGuarded === false) {
            $this->getGuarded = $this->getInstanceClassForUse()->getGuarded();
        }
        return $this->getGuarded;
    }
    public function setGuarded($getGuarded)
    {
        $this->getGuarded = $getGuarded;
    }
    
    /**
     * KeyName
     */
    public function getPrimaryKey()
    {
        return $this->getKeyName();
    }
    public function setPrimaryKey($getKeyName)
    {
        return $this->setKeyName($getKeyName);
    }
    public function getKeyName()
    {
        if ($this->getKeyName === false) {
            $this->getKeyName = $this->getInstanceClassForUse()->getKeyName();
        }
        return $this->getKeyName;
    }
    public function setKeyName($getKeyName)
    {
        $this->getKeyName = $getKeyName;
    }
    
    /**
     * KeyType
     */
    public function getKeyType()
    {
        if ($this->getKeyType === false) {
            $this->getKeyType = $this->getInstanceClassForUse()->getKeyType();
        }
        return $this->getKeyType;
    }
    public function setKeyType($getKeyType)
    {
        $this->getKeyType = $getKeyType;
    }
    
    public function getIncrementing()
    {
        if ($this->getIncrementing === false) {
            $this->getIncrementing = $this->getInstanceClassForUse()->getIncrementing();
        }
        return $this->getIncrementing;
    }
    public function setIncrementing($getIncrementing)
    {
        $this->getIncrementing = $getIncrementing;
    }
    
    public function getForeignKey()
    {
        if ($this->getForeignKey === false) {
            $this->getForeignKey = $this->getInstanceClassForUse()->getForeignKey();
        }
        return $this->getForeignKey;
    }
    public function setForeignKey($getForeignKey)
    {
        $this->getForeignKey = $getForeignKey;
    }


}
