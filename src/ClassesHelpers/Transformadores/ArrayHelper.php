<?php

declare(strict_types=1);

namespace Support\ClassesHelpers\Transformadores;

use Log;
use ArgumentCountError;
use Symfony\Component\Inflector\Inflector;


class ArrayHelper
{
    /**
     * Retorna Nome no Singular caso nao exista, e minusculo
     */
    public static function setAndPreservingOldDataConvertingToArray($array, $tableName, $tableClass)
    {
        if (is_array($array[$tableName])) {
            $array[$tableName][] = $tableClass;
            return $array;
        }
    
        $array[$tableName] = [
            $tableClass,
            $array[$tableName]
        ];
        return $array;
    }

    public static function includeKeyFromAtribute($oldArray, $attributeFromArray)
    {
        $newArray = [];
        foreach ($oldArray as $column) {
            $newArray[$column[$attributeFromArray]] = $column;
        }
        return $newArray;
    }
}
