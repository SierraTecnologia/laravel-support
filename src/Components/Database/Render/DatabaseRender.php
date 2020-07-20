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
use Illuminate\Support\Collection;
use Support\Services\EloquentService;
use Support\Patterns\Parser\ComposerParser;
use Illuminate\Support\Facades\Cache;
use Support\Elements\Entities\RelationshipEntity;
use Support\Components\Database\Types\Type;
use Log;
use Support\Components\Database\Schema\SchemaManager;

use Support\Utils\Modificators\ArrayModificator;
use Support\Utils\Inclusores\ArrayInclusor;
use Support\Utils\Modificators\StringModificator;
use Support\Utils\Extratores\ClasserExtractor;

use Support\Patterns\Parser\ParseClass;

use Support\Contracts\Support\Arrayable;
use Support\Contracts\Support\ArrayableTrait;
use Support\Traits\Debugger\HasErrors;

class DatabaseRender implements Arrayable
{
    use HasErrors, ArrayableTrait;

    /****************************************
     * Eloquent CLasse (Work and Register in Databse)
     **************************************/
    public $eloquentClasses;
    public $eloquentRenders;

    
    /**
     * From Data Table
     */
    public $mapperTableToClasses = [];
    public $mapperParentClasses = [];
    public $mapperClasserProcuracao = [];

    /**
     * Para Cada Tabela Pega as Classes Correspondentes
     */
    public $dicionarioTablesRelations = [];


    /**
     * Loaders.. Independete, carrega todos
     */
    // From  Datatavle
    public $displayTables = [];
    // From  Eloquent
    public $displayClasses; // igual eloqunetRenders


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


    protected function render()
    {
        $selfInstance = $this;
        // Cache In Minutes
        $value = Cache::remember(
            'sitec_database', 30, function () {
                Log::debug(
                    'Render Database -> Renderizando'
                );
                // $classUniversal = false; // for reference in debug
                $this->eloquentRenders = $this->returnEloquentRenders($this->eloquentClasses);
                $this->renderTables();
            
                $this->registerAndMapperDisplayClassesFromEloquentRenders();

                // // Debug Temp
                // $classUniversal = false; // for reference in debug, @todo ver se usa classe nessas 2 funcoes aqui abaixo

                // Reordena
                $this->sortArrays();

                return $this->toArray();
            }
        );
        $this->setArray($value);
    }



    protected function sortArrays()
    {
        // Ordena Pelo Indice
        ksort($this->mapperTableToClasses);
        ksort($this->dicionarioTablesRelations);
    }


    /**
     * Nivel 2
     */
    private function returnEloquentRenders($eloquentClasses): Collection
    {
        return $eloquentClasses->map(
            function ($filePath, $class) {
                return $this->mapperEloquentRenderForClass(
                    $this->buildEloquentRenderForClass($class)
                );
            }
        )->reject(
            function ($class) {
                if (!$class) {
                    return true;
                }
                return false;
            }
        );
    }
    protected function registerAndMapperDisplayClassesFromEloquentRenders()
    {
        // Remove as Classes que não sao Finais
        $this->eloquentRenders = $this->eloquentRenders->reject(
            function ($class) {
                if ($this->isForIgnoreClass($class->getModelClass())) {
                    return true;
                }
                return false;
            }
        )
        ->values()->all();


        foreach ($this->eloquentRenders as $eloquentRender) {
            $this->registerAndMapperDisplayClassesFromEloquentRender($eloquentRender);
        }
    }

    /**
     * Chamado de Fora Tbm
     */
    public function registerAndMapperDisplayClassesFromEloquentRender(EloquentRender $eloquentRender)
    {
        $this->loadMapperBdRelations($eloquentRender->getTableName(), $eloquentRender->getRelations());

        // Guarda Dados Carregados do Eloquent
        $this->displayClasses[$eloquentRender->getModelClass()] = $eloquentRender->toArray();

        // Guarda Errors
        $this->mergeErrors($eloquentRender->getErrors());
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

    /**
     * Nivel 3 - Chamado no Mount
     */
    public function mapperEloquentRenderForClass(EloquentRender $eloquentRender)
    {
        if (!$eloquentRender->getTableName()) {
            return false;
        }
        if ($eloquentRender->hasError()) {
            return false;
        }

        $this->loadMapperTableToClasses($eloquentRender->getTableName(), $eloquentRender->getModelClass());
        return $eloquentRender;
    }



    /**
     * Verificadores
     */


    public function getEloquentClasses(): Collection
    {
        return collect($this->eloquentClasses);
    }
    

}
