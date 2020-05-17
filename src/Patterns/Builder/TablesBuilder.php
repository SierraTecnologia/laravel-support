<?php

declare(strict_types=1);


namespace Support\Patterns\Builder;

use Support\Utils\Modificators\ArrayModificator;
use Support\Utils\Modificators\StringModificator;
use Support\Traits\Coder\GetSetTrait;
use Support\Components\Database\Schema\Table;
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
     * @var    string
     * @getter true
     * @setter false
     */
    protected $tables = [];

    /**
     * Nome da Classe
     *
     * @var    string
     * @getter true
     * @setter false
     */
    protected $relationTables = [];


    public function __construct(Array $tablesList)
    {
        $this->builder($tablesList);
    }

    protected function builder(Array $tables): void
    {
        foreach ($tables as $table){
            $this->builderTable($table);
        }
    }

    protected function builderTable(Table $table): Array
    {
        $columns = ArrayModificator::includeKeyFromAtribute($table->exportColumnsToArray(), 'name');
        $indexes = $table->exportIndexesToArray();
        $tableData = [
            'name' => $table->getName(),
            'columns' => $columns,
            'indexes' => $indexes
        ];

        if (!$primary = $this->returnPrimaryKeyFromIndexes($indexes)) {
            return $this->relationTables[$table->getName()] = $tableData;
        }

        $tableData['key'] = $primary;
        $tableData['displayName'] = $this->getDisplayName($table, $columns);
        
        return $this->tables[$this->returnRelationPrimaryKey($table->getName(), $primary)] = $tableData;
    }

    /**
     * Nivel 3
     */
    private function returnRelationPrimaryKey(String $tableName, String $primary)
    {
        return StringModificator::singularizeAndLower($tableName).'_'.$primary;
    }

    private function returnPrimaryKeyFromIndexes(Array $indexes)
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

    private function getDisplayName(Table $listTable, $columns = false)
    {

        // Qual coluna ira mostrar em uma Relacao ?
        if ($listTable->hasColumn('name')) {
            return 'name';
        } 
        if ($listTable->hasColumn('displayName')) {
            return 'displayName';
        }

        if (!$columns) {
            return false;
        }
        foreach ($columns as $column) {
            if ($column['type']['name'] == 'varchar') {
                return $column['name'];
            }
        }
        return false;
    }

}
