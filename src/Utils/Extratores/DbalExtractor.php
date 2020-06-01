<?php

declare(strict_types=1);

namespace Support\Utils\Extratores;

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
        // dd($where);
        return $where;
    }

    public static function generateWhereFromData($data, $indices)
    {
        $wheresArray = [];
        foreach ($indices as $index) {
            if(is_object($index)) {
                $type = $index->type;
                $columns = $index->columns;
            } else {
                $type = $index['type'];
                $columns = $index['columns'];
            }
            if ($type == 'PRIMARY' || $type == 'UNIQUE') {
                // dd($data, $columns);
                // Caso não tenha nada a procurar, entao pula
                if (!empty($generateWhere = DbalExtractor::generateWhere(
                    $columns,
                    $data
                ))) {
                    $wheresArray[] = $generateWhere;
                }
            }
        }
        return collect($wheresArray);
    }

}
