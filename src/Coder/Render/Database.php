<?php

namespace Support\Coder\Render;

use Exception;
use ErrorException;
use LogicException;
use OutOfBoundsException;
use RuntimeException;
use TypeError;
use Illuminate\Database\Eloquent\Relations\Relation;
use ReflectionClass;
use ReflectionMethod;
use Symfony\Component\Inflector\Inflector;
use Illuminate\Support\Collection;
use Support\Services\EloquentService;
use Support\Coder\Parser\ComposerParser;
use Illuminate\Support\Facades\Cache;
use Support\ClassesHelpers\Development\HasErrors;
use Support\Coder\Entitys\Relationship;
use Support\Coder\Discovers\Database\Types\Type;

class Database
{
    use HasErrors;

    /****************************************
     * Eloquent CLasse
     **************************************/
    public $eloquentClasses;



    
    public $displayClasses;
    
    /**
     * From Data Table
     */
    public $mapperTableToClasses;
    public $mapperPrimaryKeys = false;

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
    public $displayTables = [];

    public function __construct($eloquentClasses)
    {
        $this->eloquentClasses = $eloquentClasses;

        $this->render();
    }

    public function toArray()
    {
        return [
            'errors' => $this->getError(),
            // Mapa
            'mapperTableToClasses' => $this->mapperTableToClasses,
            
            // Dicionario
            'mapperPrimaryKeys' => $this->mapperPrimaryKeys,
            'displayTables' => $this->displayTables,
            
            // Informacao IMportante
    
            // Dados GErados
            'totalRelations' => $this->totalRelations,


            // Backup
            'displayClasses' => $this->displayClasses,
        ];
    }

    public function setArray($data)
    {
        $this->setError($data['errors']);
        // Mapa
        $this->mapperTableToClasses = $data['mapperTableToClasses'];
        // Dicionario
        $this->mapperPrimaryKeys = $data['mapperPrimaryKeys'];
        $this->displayTables = $data['displayTables'];
            
        // Informacao IMportante

        // Dados GErados
        $this->totalRelations = $data['totalRelations'];
            
        // Backup
        $this->displayClasses = $data['displayClasses'];
    }

    public function display()
    {
        dd(
            'errors',
            $this->getError(),
            // Mapa
            'mapperTableToClasses',
            $this->mapperTableToClasses,
            
            // Dicionario
            'mapperPrimaryKeys',
            $this->mapperPrimaryKeys,
            'displayTables',
            $this->displayTables,
            // Backup
            'displayClasses',
            $this->displayClasses,
            
            // Informacao IMportante
    
            // Dados GErados
            'totalRelations',
            $this->totalRelations


        );
    }

    protected function render()
    {
        $selfInstance = $this;
        // Cache In Minutes
        $value = Cache::remember('sitec_database', 30, function () use ($selfInstance) {
            try {
                $this->eloquentClasses = $this->eloquentClasses->map(function($file, $class) {
                    return new Eloquent($class);
                })->values()->all();
                
                $selfInstance->renderClasses();
                $selfInstance->getListTables();
            } catch(SchemaException|DBALException $e) {
                // @todo Tratar, Tabela Nao existe
                $this->setError($e->getMessage());
                
            } catch(\Symfony\Component\Debug\Exception\FatalThrowableError $e) {
                $this->setError($e->getMessage());
                // @todo Armazenar Erro em tabela
                // dd($e);
                //@todo fazer aqui
            } catch(\Exception $e) {
                $this->setError($e->getMessage());
                // dd($e);
            } catch(\Throwable $e) {
                $this->setError($e->getMessage());
                // dd($e);
                // @todo Tratar aqui
            }

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
            $this->displayClasses[$eloquentService->getModelClass()] = $eloquentService->toArray();

            // Guarda Errors
            $this->setError($eloquentService->getError());
        }
    }




    protected function getListTables()
    {
        $this->mapperPrimaryKeys = [];
        $tables = [];
        Type::registerCustomPlatformTypes();

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
                        $this->mapperPrimaryKeys[$singulariRelationName.'_'.$primary] = [
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

        $this->displayTables = $tables;
        
        return $this->mapperPrimaryKeys;
    }


}
