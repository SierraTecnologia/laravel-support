<?php

namespace Support\Components\Database\Render;

use Symfony\Component\Debug\Exception\FatalThrowableError;
use Symfony\Component\Debug\Exception\FatalErrorException;
use Exception;
use ErrorException;
use LogicException;
use OutOfBoundsException;
use RuntimeException;
use TypeError;
use Throwable;
use Watson\Validating\ValidationException;
use Illuminate\Database\Eloquent\Relations\Relation;
use ReflectionClass;
use ReflectionMethod;
use Symfony\Component\Inflector\Inflector;
use Illuminate\Support\Collection;
use Support\Services\EloquentService;
use Support\Components\Coders\Parser\ComposerParser;
use Illuminate\Support\Facades\Cache;
use Support\Elements\Entities\Relationship;
use Support\Components\Database\Types\Type;
use Log;
use Support\Components\Database\Schema\SchemaManager;
use Support\Helpers\Modificators\ArrayModificator;
use Support\Helpers\Inclusores\ArrayInclusor;
use Support\Helpers\Modificators\StringModificator;
use Support\Helpers\Development\HasErrors;

use Support\Components\Coders\Parser\ParseClass;

class Database
{
    use HasErrors;

    /****************************************
     * Eloquent CLasse (Work and Register in Databse)
     **************************************/
    public $eloquentClasses;

    
    /**
     * From Data Table
     */
    public $mapperTableToClasses = [];
    public $mapperParentClasses = [];
    public $mapperClasserProcuracao = [];

    /**
     * Para Cada Tabela Pega as Classes Correspondentes
     */
    public $dicionarioPrimaryKeys;
    public $dicionarioTablesRelations = [];


    /**
     * Loaders.. Independete, carrega todos
     */
    // From  Datatavle
    public $displayTables = [];
    // From  Eloquent
    public $displayClasses;


    /**
     * Aplication Log or Errors
     */
    public $tempAppTablesWithNotPrimaryKey = [];
    public $tempErrorClasses = [];
    public $tempIgnoreClasses = [];


    /**
     * Attributes to Array Mapper
     */
    public static $mapper = [
        'Dicionario' => [
            'dicionarioTablesRelations',
            'dicionarioPrimaryKeys',
        ],
        'Mapper' => [
            'mapperTableToClasses',
            'mapperParentClasses',
            'mapperClasserProcuracao',
        ],
        'Leitoras' => [
            'displayTables',
            'displayClasses',
        ],
        'AplicationTemp' => [
            'tempAppTablesWithNotPrimaryKey',
            'tempErrorClasses',
            'tempIgnoreClasses'
        ],

        // Esse eh manual pq pera da funcao
        // 'Errors' => [
        //     'errors',
        // ]
    ];

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
        // $this->display();
    }

    public function registerMapperClassParents($className, $classParent)
    {
        if (is_null($className) || empty($className) || is_null($classParent) || empty($classParent) || isset($this->mapperParentClasses[$className])) {
            return false;
        }

        // Ignora Oq nao serve
        if (in_array(
            ParseClass::getClassName($classParent),
            ParseClass::$typesIgnoreName['model']
        )) {
            return false;
        }

        $this->mapperParentClasses[$className] = $classParent;
    }

    public function toArray()
    {
        $dataToReturn = [];
        $mapper = self::$mapper;
        foreach ($mapper as $indice=>$dataArray) {
            $dataToReturn[$indice] = [];
            foreach ($dataArray as $atributeNameVariable) {
                $dataToReturn[$indice][$atributeNameVariable] = $this->$atributeNameVariable;
            }
        }

        $dataToReturn['Errors'] = [];
        $dataToReturn['Errors']['errors'] = $this->getErrors();

        return $dataToReturn;

        // return [
            
        //     'Dicionario' => [
        //         // Dados GErados
        //         'dicionarioTablesRelations' => $this->dicionarioTablesRelations,
        //         'dicionarioPrimaryKeys' => $this->dicionarioPrimaryKeys,
        //     ],

        //     'Mapper' => [
        //         /**
        //          * Mapper
        //          */
        //         'mapperTableToClasses' => $this->mapperTableToClasses,
        //         'mapperParentClasses' => $this->mapperParentClasses,
        //     ],
            
        //     'Leitoras' => [
        //         // Leitoras
        //         'displayTables' => $this->displayTables,
        //         'displayClasses' => $this->displayClasses,
        //     ],
    

        //     /**
        //      * Sistema
        //      */
        //     // Ok
            
        //     'AplicationTemp' => [
        //         // Nao ok
        //         'tempAppTablesWithNotPrimaryKey' => $this->tempAppTablesWithNotPrimaryKey,
        //         // 'classes' => [],

        //     ],
        //     'Errors' => [
        //         /**
        //          * Errors 
        //          **/
        //         'errors' => $this->getError(),

        //     ],
        // ];
    }

    public function setArray($datas)
    {
        $mapper = self::$mapper;
        foreach ($mapper as $indice=>$mapperValue) {
            if (isset($datas[$indice])) {
                foreach ($mapperValue as $atributeNameVariable) {
                    $this->$atributeNameVariable = $datas[$indice][$atributeNameVariable];
                }
            }
        }

        if (isset($datas['Errors'])) {
            if (isset($datas['Errors']['errors'])) {
                $this->mergeErrors($datas['Errors']['errors']);
            }
        }
        // foreach ($datas as $indice=>$data) {
        //     if ($indice==='Dicionario') {
        //         $this->dicionarioTablesRelations = $data['dicionarioTablesRelations'];
        //         // Dicionario
        //         $this->dicionarioPrimaryKeys = $data['dicionarioPrimaryKeys'];
        //     }
        //     if ($indice==='Mapper') {
        //         // Mapa
        //         $this->mapperTableToClasses = $data['mapperTableToClasses'];
        //         $this->mapperParentClasses = $data['mapperParentClasses'];
        //     }


        //     if ($indice==='Leitoras') {
        //         $this->displayTables = $data['displayTables'];
        //         $this->displayClasses = $data['displayClasses'];
        //     }


        //     if ($indice==='AplicationTemp') {
        //         $this->tempAppTablesWithNotPrimaryKey = $data['tempAppTablesWithNotPrimaryKey'];
        //     }
        //     if ($indice==='Errors') {
        //         if (isset($data['errors'])) {
        //             $this->mergeErrors($data['errors']);
        //         }
        //     }


        // }
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
    }

    protected function render()
    {
        $selfInstance = $this;
        // Cache In Minutes
        $value = Cache::remember('sitec_database', 30, function () {
            Log::debug(
                'Render Database -> Renderizando'
            );
            try {
                $classUniversal = false; // for reference in debug
                $this->eloquentClasses = $this->returnEloquents($this->eloquentClasses);

                // Cria mapeamento de classes antes de remover as invalidas
                $this->renderMappersClasses();
                $this->renderTables();

                // Remove as Classes que não sao Finais
                $this->eloquentClasses = $this->eloquentClasses->reject(function($class) {
                    $classUniversal = $class->getModelClass(); // for reference in debug
                    // For Debug
                    if ( $this->isForIgnoreClass($class->getModelClass())) {
                        return true;
                    }
                    return false;
                })
                ->values()->all();
                
                $this->renderClasses();

                // Debug Temp
                $classUniversal = false; // for reference in debug, @todo ver se usa classe nessas 2 funcoes aqui abaixo

                // Reordena
                $this->sortArrays();
            } catch(SchemaException|DBALException $e) {
                dd(
                    'Aqui nao era pra cair pois tem outro',
                    $e
                );
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
                dd(
                    'Aqui nao era pra cair pois tem outro',
                    $e
                );
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

            return $this->toArray();
        });
        $this->setArray($value);
    }



    protected function sortArrays()
    {
        // Ordena Pelo Indice
        ksort($this->mapperTableToClasses);
        ksort($this->dicionarioTablesRelations);
    }


    protected function renderMappersClasses()
    {
        foreach ($this->eloquentClasses as $eloquentService) {
            $this->loadMapperTableToClasses($eloquentService->getTableName(), $eloquentService->getModelClass());
        }
    }

    protected function renderClasses()
    {
        foreach ($this->eloquentClasses as $eloquentService) {
            $this->loadMapperBdRelations($eloquentService->getTableName(), $eloquentService->getRelations());


            // Guarda Dados Carregados do Eloquent
            $this->displayClasses[$eloquentService->getModelClass()] = $eloquentService->toArray();

            // Guarda Errors
            $this->mergeErrors($eloquentService->getErrors());
        }
    }



    /**
     * Nivel 2
     */
    protected function renderTables()
    {
        $this->dicionarioPrimaryKeys = [];
        $tables = [];
        Type::registerCustomPlatformTypes();
        $listTables = SchemaManager::listTables();
        // return $this->getSchemaManagerTable()->getIndexes(); //@todo indexe


        foreach ($listTables as $listTable){
            $columns = ArrayModificator::includeKeyFromAtribute($listTable->exportColumnsToArray(), 'name');
            $indexes = $listTable->exportIndexesToArray();

            // Salva Primaria
           
            if (!$primary = $this->loadMapperPrimaryKeysAndReturnPrimary($listTable->getName(), $indexes)) {
                // @todo VEridica aqui
                // $this->setWarnings(
                //     'Tabela sem primary key: '.$listTable->getName(),
                //     [
                //         'table' => $listTable->getName(),
                //     ],
                //     [
                //         'indexes' => $indexes
                //     ]
                // );

                $this->tempAppTablesWithNotPrimaryKey[$listTable->getName()] = [
                    'name' => $listTable->getName(),
                    'columns' => $columns,
                    'indexes' => $indexes
                ];

            } else {
                $tables[$listTable->getName()] = [
                    'name' => $listTable->getName(),
                    'columns' => $columns,
                    'indexes' => $indexes
                ];

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
        }

        $this->displayTables = $tables;
    }

    private function loadDisplayClasses($tableName, $indexes)
    {
        $primary = false;
        if (!empty($indexes)) {
            foreach ($indexes as $index) {
                if ($index['type'] == 'PRIMARY') {
                    $primary = $index['columns'][0];
                    $singulariRelationName = StringModificator::singularizeAndLower($tableName);
                    $this->dicionarioPrimaryKeys[$singulariRelationName.'_'.$primary] = [
                        'name' => $tableName,
                        'key' => $primary,
                        'label' => 'name'
                    ];
                }
            }
        }

        return $primary;
    }

    /**
     * Nivel 3
     */
    private function loadMapperPrimaryKeysAndReturnPrimary($tableName, $indexes)
    {
        $primary = false;
        if (!empty($indexes)) {
            foreach ($indexes as $index) {
                if ($index['type'] == 'PRIMARY') {
                    $primary = $index['columns'][0];
                    $singulariRelationName = StringModificator::singularizeAndLower($tableName);
                    $this->dicionarioPrimaryKeys[$singulariRelationName.'_'.$primary] = [
                        'name' => $tableName,
                        'key' => $primary,
                        'label' => 'name'
                    ];
                }
            }
        }

        return $primary;
    }


    /**
     * Nivel 3
     */
    private function loadMapperTableToClasses(string $tableName, string $tableClass)
    {
        // Guarda Classe por Table
        if (isset($this->mapperTableToClasses[$tableName])) {
            $this->mapperTableToClasses = ArrayInclusor::setAndPreservingOldDataConvertingToArray(
                $this->mapperTableToClasses,
                $tableName,
                $tableClass
            );
            // @todo Ignorar classes que uma extend a outra
            return $this->setWarnings(
                'Duas classes para a mesma tabela: '.$tableName,
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
    private function loadMapperBdRelations(string $tableName, $relations)
    {
        // Pega Relacoes
        if (empty($relations)) {
            return ;
        }

    
        foreach ($relations as $relation) {
            try {
                $tableTarget = $relation['name'];
                $tableOrigin = $tableName;

                $singulariRelationName = StringModificator::singularizeAndLower($relation['name']);
                $tableNameSingulari = StringModificator::singularizeAndLower($tableName);

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
                if (!isset($this->dicionarioTablesRelations[$novoIndice])) {
                    $this->dicionarioTablesRelations[$novoIndice] = [
                        'name' => $novoIndice,
                        'table_origin' => $tableOrigin,
                        'table_target' => $tableTarget,
                        'pivot' => 0,
                        'type' => $type,
                        'relations' => []
                    ];
                }
                $this->dicionarioTablesRelations[$novoIndice]['relations'][] = $relation;
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
            // } catch (\Exception $e) {
                dd(
                    'LaravelSupport>Database>> Não era pra Cair Erro aqui',
                    $e,
                    $relation,
                    $relation->name,
                    $relation->type,
                    $eloquentService->getTableName(),
                    $tableNameSingulari,
                    $singulariRelationName
                    // StringModificator::singularizeAndLower($relation->name).'_'.$relation->type.'_'.StringModificator::singularizeAndLower($eloquentService->getTableName()),
                    // StringModificator::singularizeAndLower($relation->name)
                    // $novoIndice
                );
            }
        }
    }


    /**
     * Nivel 3
     */
    private function returnEloquentForClasss($className)
    {
        $classUniversal = false; // for reference in debug

        try {
            $eloquent = new Eloquent($className);
            $classUniversal = $className; // for reference in debug
            $this->registerMapperClassParents($className, $eloquent->parentClass);
            return $eloquent;
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
        return false;
    }


    /**
     * Nivel 3
     */
    private function returnEloquents($eloquentClasses)
    {
        return $eloquentClasses->map(function($filePath, $class) {
            return $this->returnEloquentForClasss($class);
        })->reject(function($class) {
            if ( !$class->getTableName()) {
                return true;
            }
            if ( $class->hasError()) {
                dd(
                    'Render Eloqunet: Error',
                    $class,
                    $class->getErrors()
                );
                return true;
            }
            return false;
        });
    }


    /**
     * Verificadores
     */


    public function getEloquentClasses()
    {
        return collect($this->eloquentClasses);
    }
    
    public function haveParent($classChild)
    {
        if (is_null($classChild) || empty($classChild) || !isset($this->mapperParentClasses[$classChild])) {
            return false;
        }

        return $this->mapperParentClasses[$classChild];
    }
    public function haveChildren($className)
    {
        if (is_null($className) || empty($className) || !in_array($className, $this->mapperParentClasses)) {
            return false;
        }

        return \Support\Helpers\Searchers\ArraySearcher::arrayIsearch($className, $this->mapperParentClasses);
    }
    public function haveTableInDatabase($className)
    {
        if (is_null($className) || empty($className)) {
            return false;
        }
        
        // // Nao funciona pois todas as tabelas (mesmo nao existentes estao aqui)
        // if (\Support\Helpers\Searchers\ArraySearcher::arrayIsearch($className, $this->mapperTableToClasses)) {
        //     return true;
        // }
        $tableName = $this->returnTableForClass($className);

        if (isset($this->displayTables[$tableName])) {
            return true;
        }

        $error = \Support\Components\Errors\TableNotExistError::make(
            $tableName,
            [
                'file' => $className
            ]
        );
        $this->setError(
            $error
        );
        $this->tempErrorClasses[$className] = $error->getDescription();
        
        return false;
    }

    /**
     * Add uma classe rejeitada para ser trocada
     */
    public function loadMapperClasserProcuracao($eloquentEntity, $classForReplaced)
    {
        Log::channel('sitec-support')->debug(
            'Database Render (Rejeitando classe nao finais): Class: '.
            $classForReplaced
        );
        $this->mapperClasserProcuracao[$classForReplaced] = $eloquentEntity;
        $this->tempIgnoreClasses[] = $classForReplaced;
    }
    public function isForIgnoreClass($className)
    {
        if (is_null($className) || empty($className)) {
            return true;
        }

        if ($childrens = $this->haveChildren($className)) {
            foreach ($childrens as $children) {
                if (ParseClass::getClassName($className) === ParseClass::getClassName($children)) {
                    // @todo Verificar outras classes que nao possue nome igual mas é filha
                    /**^ "Chieldren"
                    ^ "Finder\Models\Digital\Infra\Ci\Build"
                    ^ "build"
                    ^ "Finder\Models\Digital\Infra\Ci\Build\GitBuild"
                    ^ "gitbuild"
                    ^ array:4 [▼
                    0 => "Finder\Models\Digital\Infra\Ci\Build\GitBuild"
                    1 => "Finder\Models\Digital\Infra\Ci\Build\HgBuild"
                    2 => "Finder\Models\Digital\Infra\Ci\Build\LocalBuild"
                    3 => "Finder\Models\Digital\Infra\Ci\Build\SvnBuild"
                    ] */


                    $this->loadMapperClasserProcuracao(
                        $children,
                        $className
                    );
                    return true;
                } 
                // else if(
                //     !in_array(
                //         ParseClass::getClassName($children),
                //         ParseClass::$typesIgnoreName['model']
                //     )
                // ) {

                // }
            }
        }

        /**
         * Caso tenha um pai com nome diferente tbm ignora
         */
        if ($parent = $this->haveParent($className)) {
            if(
                !in_array(
                    ParseClass::getClassName($parent),
                    ParseClass::$typesIgnoreName['model']
                ) && ParseClass::getClassName($className) !== ParseClass::getClassName($parent)
            ) {
                $this->loadMapperClasserProcuracao(
                    $parent,
                    $className
                );
                return true;
            }
        }

        return !$this->haveTableInDatabase($className);
    }



    public function returnTableForClass($className)
    {
        if (is_null($className) || empty($className)) {
            return false;
        }
        // Nao funciona pois todas as tabelas (mesmo nao existentes estao aqui)
        if (!$find = \Support\Helpers\Searchers\ArraySearcher::arrayIsearch($className, $this->mapperTableToClasses)) {
            return false;
        }

        if (is_array($find)) {
            return $find[0];
        }
        return $find;
    }

}
