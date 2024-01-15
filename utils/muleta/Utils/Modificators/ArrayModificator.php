<?php

declare(strict_types=1);

namespace Muleta\Utils\Modificators;

use Log;


class ArrayModificator
{
    public static function includeKeyFromAtribute($oldArray, $attributeFromArray): array
    {
        $newArray = [];
        foreach ($oldArray as $column) {
            $newArray[$column[$attributeFromArray]] = $column;
        }
        return $newArray;
    }


    /**
     * Se nao for um array, faz virar um adicionando o indexe se quiser;
     *
     * @return array
     */
    public static function convertToArrayWithIndex($arrayOrString, $index): array
    {
        if (is_array($arrayOrString)) {
            return $arrayOrString;
        }
        return [
            $index => $arrayOrString
        ];
    }


    /**
     * Se nao for um array, faz virar um adicionando o indexe se quiser;
     */
    public static function multiExplode(array $arrayOrString, string $stringToArray): array
    {
        $cont = 1;
        foreach ($arrayOrString as $a) {
            if ($cont>1) {
                $stringToArray = implode($a, $stringToArray);
            }
            $stringToArray = explode($a, $stringToArray);
            $cont = $cont +1;
        }
        return $stringToArray;
    }
}
