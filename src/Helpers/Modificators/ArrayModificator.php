<?php

declare(strict_types=1);

namespace Support\Helpers\Modificators;

use Log;
use ArgumentCountError;
use Symfony\Component\Inflector\Inflector;


class ArrayModificator
{
    public static function includeKeyFromAtribute($oldArray, $attributeFromArray)
    {
        $newArray = [];
        foreach ($oldArray as $column) {
            $newArray[$column[$attributeFromArray]] = $column;
        }
        return $newArray;
    }


    /**
     * Se nao for um array, faz virar um adicionando o indexe se quiser;
     */
    public static function convertToArrayWithIndex($arrayOrString, $index)
    {
        if (is_array($arrayOrString)) {
            return $arrayOrString;
        }
        return [
            $index => $arrayOrString
        ];
    }
}
