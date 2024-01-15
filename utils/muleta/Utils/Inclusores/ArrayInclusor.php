<?php

declare(strict_types=1);

namespace Muleta\Utils\Inclusores;

use Log;
use ArgumentCountError;


class ArrayInclusor
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
}
