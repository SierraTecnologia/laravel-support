<?php

declare(strict_types=1);


namespace Support\Components\Database\Mount;


use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Support\Result\RelationshipResult;
use SierraTecnologia\Crypto\Services\Crypto;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Support\Components\Database\DatabaseUpdater;
use Support\Components\Database\Schema\Column;
use Support\Components\Database\Schema\Identifier;
use Support\Components\Database\Schema\SchemaManager;
use Support\Components\Database\Schema\Table;
use Support\Components\Database\Types\Type;
use Support\Components\Coders\Parser\ParseModelClass;
use Support\Utils\Modificators\StringModificator;
use Support\Utils\Extratores\ArrayExtractor;
use Support\Components\Coders\Parser\ComposerParser;

use Support\Elements\Entities\DatabaseEntity;
use Support\Elements\Entities\EloquentEntity;
use Support\Elements\Entities\Relationship;
use Illuminate\Support\Facades\Cache;

use Log;

class TablesBuilder
{
    protected $tables = [];
    protected $relationTables = [];


    public function __construct($eloquentClasses)
    {
        Log::debug(
            'Mount Database -> Iniciando'
        );
        $this->eloquentClasses = $eloquentClasses;

        $this->render();
    }

    protected function builderTables($tables)
    {
        foreach ($tables as $table){
            $this->builderTable($table);
        }
    }

    protected function builderTable($table)
    {
        $columns = ArrayModificator::includeKeyFromAtribute($listTable->exportColumnsToArray(), 'name');
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
