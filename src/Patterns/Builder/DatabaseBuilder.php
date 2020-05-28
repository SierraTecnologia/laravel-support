<?php
/**
 * @todo deletar ussi aqui nao usa
 */


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


}
