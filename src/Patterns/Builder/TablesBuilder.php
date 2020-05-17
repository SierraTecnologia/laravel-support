<?php

declare(strict_types=1);


namespace Support\Patterns\Builder;

use Support\Utils\Modificators\ArrayModificator;
use Support\Utils\Modificators\StringModificator;
use Support\Traits\Coder\GetSetTrait;

use Log;

class TablesBuilder
{
    /**
     * Atributos
     */
    use GetSetTrait;


    /**
     * Nome da Classe
     *
     * @var string
     * @getter true
     * @setter false
     */
    protected $tables = [];

    /**
     * Nome da Classe
     *
     * @var string
     * @getter true
     * @setter false
     */
    protected $relationTables = [];


    public function __construct($tablesList)
    {
        $this->builder($tablesList);
    }

    protected function builder($tables)
    {
        foreach ($tables as $table){
            $this->builderTable($table);
        }
    }

    protected function builderTable($table)
    {
        $columns = ArrayModificator::includeKeyFromAtribute($table->exportColumnsToArray(), 'name');
        $indexes = $listTable->exportIndexesToArray();
        $tableData = [
            'name' => $listTable->getName(),
            'columns' => $columns,
            'indexes' => $indexes
        ];

        if (!$primary = $this->returnPrimaryKeyFromIndexes($table->exportIndexesToArray())) {
            return $this->relationTables[$tableName->getName()] = $tableData;
        }

        $tableData['key'] = $primary;
        $tableData['displayName'] = $this->getDisplayName($table);
        
        return $this->tables[$this->returnRelationPrimaryKey($tableName->getName(), $primary)] = $tableData;
    }

    /**
     * Nivel 3
     */
    private function returnRelationPrimaryKey($tableName, $primary)
    {
        return StringModificator::singularizeAndLower($tableName).'_'.$primary;
    }

    private function returnPrimaryKeyFromIndexes($indexes)
    {
        $primary = false;
        if (!empty($indexes)) {
            foreach ($indexes as $index) {
                if ($index['type'] == 'PRIMARY') {
                    return $index['columns'][0];
                }
            }
        }

        return $primary;
    }

    private function getDisplayName($listTable)
    {

        // Qual coluna ira mostrar em uma Relacao ?
        if ($listTable->hasColumn('name')) {
            return 'name';
        } 
        if ($listTable->hasColumn('displayName')) {
            return 'displayName';
        }
        foreach ($tables[$listTable->getName()]['columns'] as $column) {
            if ($column['type']['name'] == 'varchar') {
                return $column['name'];
            }
        }
        return false;
    }

}
