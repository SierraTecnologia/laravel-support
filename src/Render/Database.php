<?php

namespace Support\Render;

use Exception;
use ErrorException;
use LogicException;
use OutOfBoundsException;
use RuntimeException;
use TypeError;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Illuminate\Database\Eloquent\Relations\Relation;
use ReflectionClass;
use ReflectionMethod;
use Symfony\Component\Inflector\Inflector;
use Illuminate\Support\Collection;
use Support\Services\EloquentService;
use Support\Parser\ComposerParser;
use Illuminate\Support\Facades\Cache;
use Support\ClassesHelpers\Development\HasErrors;
use Support\Elements\Entities\Relationship;
use Support\Discovers\Database\Types\Type;
use Log;
use Support\ClassesHelpers\Development\ArrayHelper;
use Support\ClassesHelpers\Development\ReturnNames;

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





    protected $tempNotFinalClasses = [];



    public function getEloquentClasses()
    {
        return collect($this->eloquentClasses);
    }

    public function addTempNotFinalClasses($class)
    {
        if (is_null($class) || empty($class) || in_array($class, $this->tempNotFinalClasses)) {
            return false;
        }
        $this->tempNotFinalClasses[] = $class;
    }
    public function isNotFinalClasses($class)
    {
        if (is_null($class) || empty($class) || in_array($class, $this->tempNotFinalClasses)) {
            return true;
        }
        return false;
    }






    /**
     * Organizacao
     */
    // Mappers para Localizacao

    public function __construct($eloquentClasses)
    {
        Log::debug(
            'Render Database -> Iniciando'
        );
        $this->eloquentClasses = $eloquentClasses;

        $this->render();

        // dd($this->tempNotFinalClasses);
        // dd($this->displayClasses['Population\Models\Market\Abouts\Info']);
        $this->display();
    }

    public function toArray()
    {
        return [
            'Mapper' => [
                /**
                 * Mapper
                 */
                'mapperTableToClasses' => $this->mapperTableToClasses,
                'mapperPrimaryKeys' => $this->mapperPrimaryKeys,
            ],
            
            'Leitoras' => [
                // Leitoras
                'displayTables' => $this->displayTables,
                'displayClasses' => $this->displayClasses,
            ],
    
            
            'Dicionario' => [
                // Dados GErados
                'totalRelations' => $this->totalRelations,
            ],

            /**
             * Sistema
             */
            // Ok
            
            'Aplication' => [
                // Nao ok
                'tables' => [],
                'classes' => [],

            ],
            'Erros' => [

                /**
                 * Erros 
                 **/
                'errors' => $this->getError()
            ],
        ];
    }

    public function setArray($datas)
    {
        foreach ($datas as $indice=>$data) {
            if ($indice==='Errors') {
                if (isset($data['errors'])) {
                    $this->setErrors($data['errors']);
                }
            }
            if ($indice==='Aplication') {


            }
            if ($indice==='Dicionario') {
                $this->totalRelations = $data['totalRelations'];
            }
            if ($indice==='Leitoras') {
                $this->displayTables = $data['displayTables'];
                $this->displayClasses = $data['displayClasses'];
            }
            if ($indice==='Mapper') {
                // Mapa
                $this->mapperTableToClasses = $data['mapperTableToClasses'];
                // Dicionario
                $this->mapperPrimaryKeys = $data['mapperPrimaryKeys'];
            }
        }
    }

    public function display()
    {
        $display = [];
        $array = $this->toArray();
        foreach ($array as $category => $infos) {
            foreach ($infos as $title => $value) {
                $display[] = $category.' > '.$title;
                $display[] = $value;
            }
        }
        dd(
            ...$display
        );
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
            Log::debug(
                'Render Database -> Renderizando'
            );
            try {
                $classUniversal = false; // for reference in debug
                $this->eloquentClasses = $this->eloquentClasses->map(function($filePath, $class) use ($selfInstance, &$classUniversal) {
                    $eloquent = new Eloquent($class);
                    $classUniversal = $class; // for reference in debug
                    $selfInstance->addTempNotFinalClasses($eloquent->parentClass);
                    return $eloquent;
                });

                // Cria mapeamento de classes antes de remover as invalidas

                // Remove Classes Invalidas
                $this->eloquentClasses = $this->eloquentClasses->reject(function($class) use ($selfInstance) {
                    $classUniversal = $class; // for reference in debug
                    // For Debug
                    if ( !$class->getTableName() || $selfInstance->isNotFinalClasses($class->getModelClass())) {
                        Log::channel('sitec-support')->error(
                            'Database Render: '.
                            $class->getModelClass().' - '.
                            print_r($selfInstance->isNotFinalClasses($class->getModelClass()), true)
                        );
                    }
                    return !$class->getTableName() || $selfInstance->isNotFinalClasses($class->getModelClass());
                })
                ->values()->all();
                $classUniversal = false; // for reference in debug, @todo ver se usa classe nessas 2 funcoes aqui abaixo
                $selfInstance->renderClasses();
                $selfInstance->getListTables();

                // Processa o Resultado
                $selfInstance->sortArrays();
            } catch(SchemaException|DBALException $e) {
                $reference = false;
                if (isset($classUniversal) && !empty($classUniversal) && is_string($classUniversal)) {
                    $reference = [
                        'model' => $classUniversal
                    ];
                } 
                // else if (isset($classUniversal) && !empty($classUniversal) && is_object($classUniversal)) {
                //     $reference = [
                //         'model' => $classUniversal
                //     ];
                // }
                // @todo Tratar, Tabela Nao existe
                $this->setErrors(
                    $e,
                    $reference
                );

            } catch(LogicException|ErrorException|RuntimeException|OutOfBoundsException|TypeError|ValidationException|FatalThrowableError|FatalErrorException|Exception|Throwable  $e) {
                $reference = false;
                if (isset($classUniversal) && !empty($classUniversal) && is_string($classUniversal)) {
                    $reference = [
                        'model' => $classUniversal
                    ];
                } 
                $this->setErrors(
                    $e,
                    $reference
                );
            }
            return $selfInstance->toArray();
        });
        $this->setArray($value);
    }



    public function sortArrays()
    {
        // Ordena Pelo Indice
        ksort($this->mapperTableToClasses);
        ksort($this->totalRelations);
    }


    public function renderClasses()
    {
        $this->mapperTableToClasses = [];
        $this->totalRelations = [];
        
        foreach ($this->eloquentClasses as $eloquentService) {


            $this->loadMapperTableToClasses($eloquentService->getTableName(), $eloquentService->getModelClass());
            $this->loadMapperBdRelations($eloquentService->getTableName(), $eloquentService->getRelations());



            // Guarda Dados Carregados do Eloquent
            $this->displayClasses[$eloquentService->getModelClass()] = $eloquentService->toArray();

            // Guarda Errors
            $this->setErrors($eloquentService->getError());
        }
    }



    /**
     * Nivel 2
     */
    public function getListTables()
    {
        $this->mapperPrimaryKeys = [];
        $tables = [];
        Type::registerCustomPlatformTypes();

        $listTables = \Support\Discovers\Database\Schema\SchemaManager::listTables();
        // return $this->getSchemaManagerTable()->getIndexes(); //@todo indexe


        foreach ($listTables as $listTable){
            $columns = [];
            foreach ($listTable->exportColumnsToArray() as $column) {
                $columns[$column['name']] = $column;
            }
            $indexes = $listTable->exportIndexesToArray();

            $tables[$listTable->getName()] = [
                'name' => $listTable->getName(),
                'columns' => $columns,
                'indexes' => $indexes
            ];

            // Salva Primaria
            if (!empty($indexes)) {
                foreach ($indexes as $index) {
                    if ($index['type'] == 'PRIMARY') {
                        $primary = $index['columns'][0];
                        $singulariRelationName = ReturnNames::singularize($listTable->getName());
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


    /**
     * Nivel 3
     */
    private function loadMapperTableToClasses(string $tableName, string $tableClass)
    {
        // Guarda Classe por Table
        if (isset($this->mapperTableToClasses[$tableName])) {
            $this->mapperTableToClasses = ArrayHelper::setAndPreservingOldDataConvertingToArray(
                $this->mapperTableToClasses,
                $tableName,
                $tableClass
            );
            return $this->setErrors(
                'Duas classes para a mesma tabela',
                [
                    'models' => $this->mapperTableToClasses[$tableName]
                ]
            );
        }
        $this->mapperTableToClasses[$tableName] = $tableClass;
    }


    /**
     * Nivel 3
     */
    private function loadMapperBdRelations(string $tableName, string $relations)
    {
            // Pega Relacoes
            if (!empty($relations)) {
                return ;
            }
        
            foreach ($relations as $relation) {
                try {
                    $tableTarget = $relation['name'];
                    $tableOrigin = $tableName;

                    $singulariRelationName = ReturnNames::singularize($relation['name']);
                    $tableNameSingulari = ReturnNames::singularize($tableName);

                    $type = $relation['type'];
                    if (Relationship::isInvertedRelation($relation['type'])) {
                        $type = Relationship::getInvertedRelation($type);
                        $novoIndice = $tableNameSingulari.'_'.$type.'_'.$singulariRelationName;
                    } else {
                        $temp = $tableOrigin;
                        $tableOrigin = $tableTarget;
                        $tableTarget = $temp;
                        $novoIndice = $singulariRelationName.'_'.$type.'_'.$tableNameSingulari;
                    }
                    if (!isset($this->totalRelations[$novoIndice])) {
                        $this->totalRelations[$novoIndice] = [
                            'name' => $novoIndice,
                            'table_origin' => $tableOrigin,
                            'table_target' => $tableTarget,
                            'pivot' => 0,
                            'type' => $type,
                            'relations' => []
                        ];
                    }
                    $this->totalRelations[$novoIndice]['relations'][] = $relation;
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
                        // ReturnNames::singularize($relation->name).'_'.$relation->type.'_'.ReturnNames::singularize($eloquentService->getTableName()),
                        // ReturnNames::singularize($relation->name)
                        // $novoIndice
                    );
                }
            }
        }
    }

}
