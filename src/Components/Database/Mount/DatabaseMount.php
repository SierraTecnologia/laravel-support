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
use Support\Patterns\Parser\ParseModelClass;
use Support\Utils\Modificators\StringModificator;
use Support\Utils\Extratores\ArrayExtractor;
use Support\Patterns\Parser\ComposerParser;

use Support\Elements\Entities\DatabaseEntity;
use Support\Elements\Entities\EloquentEntity;
use Support\Elements\Entities\RelationshipEntity;
use Illuminate\Support\Facades\Cache;

use Log;
use Support\Contracts\Support\Arrayable;
use Support\Contracts\Support\ArrayableTrait;

use Support\Exceptions\Coder\EloquentHasErrorException;
use Support\Exceptions\Coder\EloquentNotExistException;
use Support\Exceptions\Coder\EloquentEntityFailedException;

class DatabaseMount implements Arrayable
{
    use ArrayableTrait;

    /**
     * Attributes to Array Mapper
     */
    public static $mapper = [
        'eloquentClasses',
        'renderDatabase',
        'ignoretedClasses',
    ];


    protected $eloquentClasses = false;
    protected $eloquentRenders = false;
    protected $eloquentEntitys = false;


    protected $renderDatabase = false;
    protected $renderDatabaseArray = false;



    protected $relationships = false;
    protected $ignoretedClasses = [];



    public function __construct($eloquentClasses)
    {
        Log::debug(
            'Mount Database -> Iniciando'
        );
        $this->eloquentClasses = $eloquentClasses;

        $this->render();
    }

    private function loadRenderDatabaseArray()
    {
        if (!$this->renderDatabase) {
            $this->renderDatabase = (new \Support\Components\Database\Render\DatabaseRender($this->eloquentClasses));
        }
        $this->renderDatabaseArray = $this->renderDatabase->toArray();
    }


    protected function render(): void
    {
        $eloquentClasses = $this->eloquentClasses;
        
        $this->loadRenderDatabaseArray();
        $renderDatabaseArray = $this->renderDatabaseArray;

        // // Persist Models With Errors @todo retirar ignoretedClasses
        // $this->ignoretedClasses = $this->eloquentClasses->diffKeys($renderDatabaseArray["Leitoras"]["displayClasses"]);
        $eloquentRenders = $this->eloquentRenders = collect($renderDatabaseArray["Leitoras"]["displayClasses"]);
        // dd(
        //     'Olaaaa Database Mount',
        //     $eloquentClasses,
        //     $this->ignoretedClasses 
        // );

        
        $this->relationships = $eloquentRenders->map(
            function ($eloquentData, $className) use ($renderDatabaseArray) {

                foreach ($eloquentData['relations'] as $relation) {
                    if (!isset($relation['origin_table_name']) || empty($relation['origin_table_name'])) {
                        $relation['origin_table_name'] = $renderDatabaseArray["Leitoras"]["displayClasses"][$relation['origin_table_class']]["tableName"];
                    }
                    if (!isset($relation['related_table_name']) || empty($relation['related_table_name'])) {
                        $relation['related_table_name'] = ArrayExtractor::returnNameIfNotExistInArray(
                            $relation['related_table_class'],
                            $renderDatabaseArray,
                            '["Leitoras"]["displayClasses"][{{index}}]["tableName"]'
                        );
                    }
                    return new RelationshipEntity($relation);
                }
            }
        );
        


        $this->eloquentEntitys = $eloquentRenders->reject(
            function ($eloquentData, $className) {
                return $this->eloquentHasError($className);
            }
        )->map(
            function ($eloquentData, $className) use ($renderDatabaseArray) {
                return (new EloquentMount($className, $renderDatabaseArray))->getEntity();
            }
        );

    }

    public function getAllEloquentsEntitys(): array
    {
        //     $this->renderDatabaseArray['AplicationTemp']['tempErrorClasses']
        // );
        return $this->eloquentEntitys->toArray();
    }

    public function getEloquentEntityFromClassName($className): EloquentEntity
    {
        $className = $this->returnProcuracaoClasse($className);
        if (empty($className)) {
            throw new EloquentNotExistException($className);
        }
        if ($this->eloquentHasError($className)) {
            throw new EloquentHasErrorException($className, $this->renderDatabaseArray['AplicationTemp']['tempErrorClasses'][$className]);
        }

        if (isset($this->eloquentEntitys->toArray()[$className])) {
            return $this->eloquentEntitys->toArray()[$className];
        }


        $eloquentRender = $this->renderDatabase->buildEloquentRenderForClass($className);
        if (!$this->renderDatabase->mapperEloquentRenderForClass($eloquentRender)) {
            throw new EloquentEntityFailedException($eloquentRender);
        }
        $this->renderDatabase->registerAndMapperDisplayClassesFromEloquentRender($eloquentRender);
        $this->loadRenderDatabaseArray();

        return $this->eloquentEntitys[$className] = (new EloquentMount($className, $this->renderDatabaseArray))->getEntity();
    }

    public function eloquentHasError($className)
    {
        return isset($this->renderDatabaseArray['AplicationTemp']['tempErrorClasses'][$className]);
    }

    public function getEloquentError($className)
    {
        return $this->renderDatabaseArray['AplicationTemp']['tempErrorClasses'][$className];
    }

    public function returnProcuracaoClasse($className)
    {
        if (isset($this->renderDatabaseArray['Mapper']['mapperClasserProcuracao'][$className])) {
            return $this->renderDatabaseArray['Mapper']['mapperClasserProcuracao'][$className];
        }
        return $className;
    }
}
