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
use Support\Elements\Entities\RelationshipEntity;
use Illuminate\Support\Facades\Cache;

use Log;
use Support\Contracts\Support\Arrayable;
use Support\Contracts\Support\ArrayableTrait;

use Support\Exceptions\Coder\EloquentHasErrorException;
use Support\Exceptions\Coder\EloquentNotExistException;

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


    protected $renderDatabase = false;
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


    protected function render(): void
    {
        $eloquentClasses = $this->eloquentClasses;
        // Cache In Minutes
        $renderDatabaseArray = Cache::remember(
            'sitec_support_render_database_'.md5(implode('|', $eloquentClasses->values()->all())), 30, function () use ($eloquentClasses) {
                Log::debug(
                    'Mount Database -> Renderizando'
                );
                $renderDatabase = (new \Support\Components\Database\Render\DatabaseRender($eloquentClasses));
                return $renderDatabase->toArray();
            }
        );
        
        // // Persist Models With Errors @todo retirar ignoretedClasses
        // $this->ignoretedClasses = $this->eloquentClasses->diffKeys($renderDatabaseArray["Leitoras"]["displayClasses"]);
        $eloquentClasses = $this->eloquentClasses = collect($renderDatabaseArray["Leitoras"]["displayClasses"]);
        // dd(
        //     'Olaaaa Database Mount',
        //     $eloquentClasses,
        //     $this->ignoretedClasses 
        // );

        $this->renderDatabase = $renderDatabaseArray;
        
        $this->relationships = $eloquentClasses->map(
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
        


        $this->entitys = $eloquentClasses->reject(
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
        //     dd($this->entitys,
        //     $this->renderDatabase['AplicationTemp']['tempErrorClasses']
        // );
        return $this->entitys->toArray();
    }

    public function getEloquentEntityFromClassName($className): EloquentEntity
    {
        $className = $this->returnProcuracaoClasse($className);
        if ($this->eloquentHasError($className)) {
            throw new EloquentHasErrorException($className, $this->renderDatabase['AplicationTemp']['tempErrorClasses'][$className]);
        }

        if (!empty($className) && isset($this->entitys->toArray()[$className])) {
            return $this->entitys->toArray()[$className];
        }

        throw new EloquentNotExistException($className);
    }

    public function eloquentHasError($className)
    {
        return isset($this->renderDatabase['AplicationTemp']['tempErrorClasses'][$className]);
    }

    public function returnProcuracaoClasse($className)
    {
        if (isset($this->renderDatabase['Mapper']['mapperClasserProcuracao'][$className])) {
            return $this->renderDatabase['Mapper']['mapperClasserProcuracao'][$className];
        }
        return $className;
    }
}
