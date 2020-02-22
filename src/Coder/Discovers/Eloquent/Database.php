<?php

namespace Support\Coder\Discovers\Eloquent;

use ErrorException;
use Illuminate\Database\Eloquent\Relations\Relation;
use ReflectionClass;
use ReflectionMethod;
use Symfony\Component\Inflector\Inflector;
use Illuminate\Support\Collection;
use Support\Services\EloquentService;
use Support\Coder\Parser\ComposerParser;

class Database
{
    public $errors = [];

    /****************************************
     * Eloquent CLasse
     **************************************/
    public $eloquentClasses;
    public $renderEloquentService;
    /**
     * From Data Table
     */
    public $renderClassesForTable;

    /**
     * Para Cada Tabela Pega as Classes Correspondentes
     */
    public $totalRelations;

    /****************************************
     * From Datatavle
     **************************************/
    /**
     * From Eloquent
     */
    public $tablesPrimaryKeys = false;
    public $tablesInfo = [];

    public function __construct($config = false)
    {
        if (!$config) {
            $config = config('sitec.discover.models_alias');
        }
        
        $this->eloquentClasses = collect((new \Support\Services\DatabaseService($config, new ComposerParser))->getAllModels())->map(function($file, $class) {
            return new EloquentService($class);
        })->values()->all();
        $this->renderClasses();

        $this->getListTables();

        $this->display();
    }

    public function getKeys()
    {
        return $this->keys;
    }

    public function setError($error)
    {
        return $this->errors[] = $error;
    }

    public function display()
    {
        dd(
            $this->errors,
            $this->tablesPrimaryKeys,
            
            $this->renderEloquentService,
            $this->renderClassesForTable,
            
            $this->totalRelations
        );
    }




    protected function renderClasses()
    {
        $this->renderClassesForTable = [];
        $this->totalRelations = [];
        foreach ($this->eloquentClasses as $eloquentService) {
            // Guarda Classe por Table
            if (isset($this->renderClassesForTable[$eloquentService->getTableName()])) {
                if (is_array($this->renderClassesForTable[$eloquentService->getTableName()])) {
                    $this->renderClassesForTable[$eloquentService->getTableName()][] = $eloquentService->getModelClass();
                } else {
                    $this->renderClassesForTable[$eloquentService->getTableName()] = [
                        $eloquentService->getModelClass(),
                        $this->renderClassesForTable[$eloquentService->getTableName()]
                    ];
                }
                $this->setError('Duas classes para a mesma tabela');
            } else {
                $this->renderClassesForTable[$eloquentService->getTableName()] = $eloquentService->getModelClass();
            }

            // Pega Relacoes
            if (!empty($relations = $eloquentService->getRelations())) {
                foreach ($relations as $relation) {
                    try {
                        $tableNameSingulari = Inflector::singularize($eloquentService->getTableName());
                        if (is_array($tableNameSingulari)) {
                            $tableNameSingulari = $tableNameSingulari[count($tableNameSingulari)-1];
                        }
                        $novoIndice = Inflector::singularize($relation->name).'_'.$relation->type.'_'.$tableNameSingulari;
                        if (!isset($this->totalRelations[$novoIndice])) {
                            $this->totalRelations[$novoIndice] = [];
                        }
                        $this->totalRelations[$novoIndice][] = [
                          $relation  
                        ];
                    } catch (\Exception $e) {
                        dd(
                            'LaravelSupport>Database>> Não era pra Cair Erro aqui',
                            $e,
                            $relation,
                            $relation->name,
                            $relation->type,
                            $eloquentService->getTableName(),
                            $tableNameSingulari,
                            Inflector::singularize($relation->name),
                            Inflector::singularize($relation->type)
                            // Inflector::singularize($relation->name).'_'.$relation->type.'_'.Inflector::singularize($eloquentService->getTableName()),
                            // Inflector::singularize($relation->name)
                            // $novoIndice
                        );
                    }
                }
            }

            // Guarda Dados Carregados do Eloquent
            $this->renderEloquentService[$eloquentService->getModelClass()] = $eloquentService->toArray();

            // Guarda Errors
            $this->setError($eloquentService->getError());
        }
    }




    protected function getListTables()
    {
        $this->tablesPrimaryKeys = [];
        $tables = [];

        $listTables = \Support\Coder\Discovers\Database\Schema\SchemaManager::listTables();




        foreach ($listTables as $listTable){
            $columns = [];
            foreach ($listTable->exportColumnsToArray() as $column) {
                $columns[$column['name']] = $column;
            }

            $tables[$listTable->getName()] = [
                'name' => $listTable->getName(),
                'columns' => $columns,
            ];

            // Salva Primaria
            if (!empty($indexes = $listTable->exportIndexesToArray())) {
                foreach ($indexes as $index) {
                    if ($index['type'] == 'PRIMARY') {
                        $primary = $index['columns'][0];
                        // dd(Inflector::singularize($listTable->getName()),$primary, Inflector::singularize($listTable->getName()).'_'.$primary);
                        $this->tablesPrimaryKeys[Inflector::singularize($listTable->getName()).'_'.$primary] = [
                            'name' => $listTable->getName(),
                            'key' => $primary,
                            'label' => 'name'
                        ];
                    }
                }
            }


            // Qual coluna ira mostrar em uma Relacao ?
            if ($listTable->hasColumn('name')) {
                $tables[$listTable->getName()]['displayName'] = 'name';
            } else if ($listTable->hasColumn('displayName')) {
                $tables[$listTable->getName()]['displayName'] = 'displayName';
            } else {
                $achou = false;
                foreach ($tables[$listTable->getName()]['columns'] as $column) {
                    if ($column['type']['name'] == 'varchar') {
                        $tables[$listTable->getName()]['displayName'] = $column['name'];
                        $achou = true;
                        break;
                    }
                }
                if (!$achou) {
                    $tables[$listTable->getName()]['displayName'] = $primary;
                }
            }
        }

        $this->tablesInfo = $tables;
        
        return $this->tablesPrimaryKeys;
    }
}
