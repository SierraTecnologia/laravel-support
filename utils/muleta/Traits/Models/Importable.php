<?php

namespace Muleta\Traits\Models;


use Muleta\Utils\Extratores\FileExtractor;
use Muleta\Utils\Extratores\StringExtractor;
use Muleta\Utils\Modificators\StringModificator;

/**
 * Adds behavior for making the model exportable to CSV and potentially other
 * formats
 */
trait Importable
{

    /**
     *
     * @return array
     */
    public static function importFromArray($line)
    {
        $arrayData = static::importMakeDataArray($line);
        return static::create(
            $arrayData
        );
    }
    public static function importMakeDataArray($line)
    {
        $arrayData = [];
        foreach ($line as $name=>$column) {
            $name = StringModificator::clean($name);
            $column = StringModificator::clean($column);
            $indice = 'obs';
            // $arrayData[$name] = $column;
            if ($name == 'DATA') {
                $indice = 'data';
            } else if ($name == 'VALOR') {
                $indice = 'value';
                $column = floatval($column);
                // $indice = 'amount';
            } else if ($name == 'TIPO') {
                // $indice = 'type';
                $indice = 'description';
            }

            $arrayData[$indice] = $column;
        }
        return $arrayData;
    }
}
