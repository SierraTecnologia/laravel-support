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
use Illuminate\Support\Facades\Cache;
use Support\ClassesHelpers\Development\HasErrors;

class Database
{
    use HasErrors;

    /****************************************
     * Eloquent CLasse
     **************************************/
    public $eloquentClasses;
    public $renderEloquentService;
    /**
     * From Data Table
     */
    public $mapperTableToClasses;

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

        $this->render($config);

        $this->display();
    }

    public function toArray()
    {
        return [
            'errors' => $this->getError(),
            // Mapa
            'mapperTableToClasses' => $this->mapperTableToClasses,
            
            // Dicionario
            'tablesPrimaryKeys' => $this->tablesPrimaryKeys,
            'tablesInfo' => $this->tablesInfo,
            
            // Informacao IMportante
    
            // Dados GErados
            'totalRelations' => $this->totalRelations,


            // Backup
            'renderEloquentService' => $this->renderEloquentService,
        ];
    }

    public function setArray($data)
    {
        $this->setError($data['errors']);
        // Mapa
        $this->mapperTableToClasses = $data['mapperTableToClasses'];
        // Dicionario
        $this->tablesPrimaryKeys = $data['tablesPrimaryKeys'];
        $this->tablesInfo = $data['tablesInfo'];
            
        // Informacao IMportante

        // Dados GErados
        $this->totalRelations = $data['totalRelations'];
            
        // Backup
        $this->renderEloquentService = $data['renderEloquentService'];
    }

    public function display()
    {
        dd(
            $this->getError(),
            // Mapa
            $this->mapperTableToClasses,
            
            // Dicionario
            $this->tablesPrimaryKeys,
            $this->tablesInfo,
            
            // Informacao IMportante
    
            // Dados GErados
            $this->totalRelations,


            // Backup
            $this->renderEloquentService
            // $this->toArray()
        );
    }

    protected function render($config)
    {
        $selfInstance = $this;
        // Cache In Minutes
        $value = Cache::remember('sitec_database', 30, function () use ($selfInstance, $config) {
            
            $selfInstance->eloquentClasses = collect((new \Support\Services\DatabaseService($config, new ComposerParser))->getAllModels())->map(function($file, $class) {
                return $class;
            })->values()->all();
            
            $selfInstance->getListTables();
            $selfInstance->renderClasses();

            return $selfInstance->toArray();
        });
        $this->setArray($value);
    }




    protected function renderClasses()
    {
        $this->mapperTableToClasses = [];
        $this->totalRelations = [];
        foreach ($this->eloquentClasses as $eloquentService) {
            // Guarda Classe por Table
            if (isset($this->mapperTableToClasses[$eloquentService->getTableName()])) {
                if (is_array($this->mapperTableToClasses[$eloquentService->getTableName()])) {
                    $this->mapperTableToClasses[$eloquentService->getTableName()][] = $eloquentService->getModelClass();
                } else {
                    $this->mapperTableToClasses[$eloquentService->getTableName()] = [
                        $eloquentService->getModelClass(),
                        $this->mapperTableToClasses[$eloquentService->getTableName()]
                    ];
                }
                $this->setError('Duas classes para a mesma tabela');
            } else {
                $this->mapperTableToClasses[$eloquentService->getTableName()] = $eloquentService->getModelClass();
            }

            // Pega Relacoes
            if (!empty($relations = $eloquentService->getRelations())) {
                foreach ($relations as $relation) {
                    try {
                        $singulariRelationName = Inflector::singularize($relation->name);
                        if (is_array($singulariRelationName)) {
                            $singulariRelationName = $singulariRelationName[count($singulariRelationName)-1];
                        }
                        $tableNameSingulari = Inflector::singularize($eloquentService->getTableName());
                        if (is_array($tableNameSingulari)) {
                            $tableNameSingulari = $tableNameSingulari[count($tableNameSingulari)-1];
                        }
                        if (Relationship::isInvertedRelation($relation->type)) {
                            $novoIndice = $tableNameSingulari.'_'.Relationship::getInvertedRelation($relation->type).'_'.$singulariRelationName;
                        } else {
                            $novoIndice = $singulariRelationName.'_'.$relation->type.'_'.$tableNameSingulari;
                        }
                        if (!isset($this->totalRelations[$novoIndice])) {
                            $this->totalRelations[$novoIndice] = [];
                        }
                        $this->totalRelations[$novoIndice][] = [
                          $relation->toArray() 
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
                            $singulariRelationName
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
                        $singulariRelationName = Inflector::singularize($listTable->getName());
                        if (is_array($singulariRelationName)) {
                            $singulariRelationName = $singulariRelationName[count($singulariRelationName)-1];
                        }
                        $this->tablesPrimaryKeys[$singulariRelationName.'_'.$primary] = [
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

    public function getKeys()
    {
        return $this->keys;
    }
}
