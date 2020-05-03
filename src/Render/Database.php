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
use Support\Elements\Entities\Relationship;
use Support\Discovers\Database\Types\Type;
use Log;
use Support\ClassesHelpers\Transformadores\ArrayHelper;
use Support\ClassesHelpers\Extratores\StringExtractor;
use Support\ClassesHelpers\Development\HasErrors;

class Database
{
    use HasErrors;

    /****************************************
     * Eloquent CLasse
     **************************************/
    public $eloquentClasses;
    
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
    public $displayTables = [];
    /**
     * From Eloquent
     */
    public $displayClasses;



    /**
     * Errors
     */
    public $logErrorsTablesNotHasPrimary = [];

    /**
     * Para guardar daos temporarios
     */
    protected $tempNotFinalClasses = [];
    protected $tempNotFinalClassesMapper = [];



    public function getEloquentClasses()
    {
        return collect($this->eloquentClasses);
    }

    public function addTempNotFinalClasses($classParent, $child)
    {
        if (is_null($classParent) || empty($classParent) || in_array($classParent, $this->tempNotFinalClasses)) {
            return false;
        }

        // @debito @todo melhorar isso aqui
        $this->tempNotFinalClasses[] = $classParent;
        $this->tempNotFinalClassesMapper[$classParent] = $child;

    }
    public function isNotFinalClasses($classParent)
    {
        if (is_null($classParent) || empty($classParent) || in_array($classParent, $this->tempNotFinalClasses)) {
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
            
            // 'Aplication' => [
            //     // Nao ok
            //     'tables' => [],
            //     'classes' => [],

            // ],
            'Erros' => [

                /**
                 * Log Erros 
                 **/
                'errors' => $this->logErrorsTablesNotHasPrimary,

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
        $value = Cache::remember('sitec_database', 30, function () {
            Log::debug(
                'Render Database -> Renderizando'
            );
            try {
                $classUniversal = false; // for reference in debug
                $this->eloquentClasses = $this->returnEloquents($this->eloquentClasses);

                // Cria mapeamento de classes antes de remover as invalidas
                $this->renderClasses();
                $this->renderTables();

                // Remove as Classes que não sao Finais
                $this->eloquentClasses = $this->eloquentClasses->reject(function($class) {
                    $classUniversal = $class->getModelClass(); // for reference in debug
                    // For Debug
                    if ( $this->isNotFinalClasses($class->getModelClass())) {
                        Log::channel('sitec-support')->error(
                            'Database Render (Rejeitando classe nao finais): ParentCLass: '.
                            $class->getModelClass().' ChildrenClass: '.
                            $this->tempNotFinalClassesMapper[$class->getModelClass()]
                        );
                        return true;
                    }
                    return false;
                })
                ->values()->all();

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
        ksort($this->totalRelations);
    }


    protected function renderClasses()
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
    protected function renderTables()
    {
        $this->mapperPrimaryKeys = [];
        $tables = [];
        Type::registerCustomPlatformTypes();

        $listTables = \Support\Discovers\Database\Schema\SchemaManager::listTables();
        // return $this->getSchemaManagerTable()->getIndexes(); //@todo indexe


        foreach ($listTables as $listTable){
            $columns = ArrayHelper::includeKeyFromAtribute($listTable->exportColumnsToArray(), 'name');
            $indexes = $listTable->exportIndexesToArray();

            // Salva Primaria
           
            if (!$primary = $this->loadMapperPrimaryKeysAndReturnPrimary($listTable->getName(), $indexes)) {
                $this->setWarnings(
                    'Tabela sem primary key: '.$listTable->getName(),
                    [
                        'table' => $listTable->getName(),
                    ],
                    [
                        'indexes' => $indexes
                    ]
                );

                $this->logErrorsTablesNotHasPrimary[$listTable->getName()] = [
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
                    $singulariRelationName = StringExtractor::singularize($tableName);
                    $this->mapperPrimaryKeys[$singulariRelationName.'_'.$primary] = [
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
            $this->mapperTableToClasses = ArrayHelper::setAndPreservingOldDataConvertingToArray(
                $this->mapperTableToClasses,
                $tableName,
                $tableClass
            );
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

                $singulariRelationName = StringExtractor::singularize($relation['name']);
                $tableNameSingulari = StringExtractor::singularize($tableName);

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
                    // StringExtractor::singularize($relation->name).'_'.$relation->type.'_'.StringExtractor::singularize($eloquentService->getTableName()),
                    // StringExtractor::singularize($relation->name)
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
            $this->addTempNotFinalClasses($eloquent->parentClass, $className);
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

}
