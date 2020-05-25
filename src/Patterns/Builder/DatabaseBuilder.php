<?php

declare(strict_types=1);


namespace Support\Patterns\Builder;

use Support\Utils\Modificators\ArrayModificator;
use Support\Utils\Modificators\StringModificator;
use Support\Traits\Coder\GetSetTrait;
use Support\Components\Database\Schema\Table;
use Log;
use Support\Contracts\Manager\BuilderAbstract;

class DatabaseBuilder extends BuilderAbstract
{
    public static $renderForChildrens = DatabaseTableBuilder::class;

    protected function renderChildrens()
    {
        return \Support\Patterns\Render\DatabaseRender::make('', $this->output)();
    }


    protected function renderData()
    {
        $data = [];

        $results = $this->getChildrens();
        foreach($results as $result) {
            $data[$result] = $this->childrenRenders[$result]->getData(); //->toArray();
        }
        
        return $data;
    }
    

    public function builder()
    {
        $results = \Support\Patterns\Render\DatabaseRender::make('', $this->output)();

        // dd($results);


        // $results = $render->getData();
        foreach ($results as $result){
            $this->builderTable($result);
        }

        dd($this->data);
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
            return $this->data[$table->getName()] = $tableData;
        }

        $tableData['key'] = $primary;
        $tableData['displayName'] = $this->getDisplayName($table, $columns);
        
        return $this->data[$this->returnRelationPrimaryKey($table->getName(), $primary)] = $tableData;
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
