<?php

declare(strict_types=1);

namespace Support\ClassesHelpers\Extratores;

use Log;

class DbalExtractor
{
    
    /**
     * Retorna Nome no Singular caso nao exista, e minusculo
     */
    public static function generateWhere($columns, $data)
    {
        $where = [];
        foreach ($columns as $column) {
            if (isset($data[$column]) && !empty($data[$column])) {
                $where[$column] = $data[$column];
                // @todo resolver
                // $where[$column] = static::cleanCodeSlug($data[$column]);
            }
        }
        return $where;
    }

    public static function generateWhereFromData($data, $indices)
    {
        $wheresArray = [];
        foreach ($indices as $index) {
            if ($index['type'] == 'PRIMARY' || $index['type'] == 'UNIQUE') {
                // Caso não tenha nada a procurar, entao pula
                if (!empty($generateWhere = DbalExtractor::generateWhere(
                    $index['columns'],
                    $data
                ))) {
                    $wheresArray[] = $generateWhere;
                }
            }
        }

        return collect($wheresArray);
    }

}
