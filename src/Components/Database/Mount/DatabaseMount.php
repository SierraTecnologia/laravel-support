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

class DatabaseMount
{
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

    protected function toArray()
    {
        $data = [];
        $data['eloquentClasses'] = $this->eloquentClasses;
        $data['renderDatabase'] = $this->renderDatabase;
        $data['ignoretedClasses'] = $this->ignoretedClasses;
        return $data;
    }


    protected function setArray($data)
    {
        if (isset($data['eloquentClasses'])) {
            $this->eloquentClasses = $data['eloquentClasses'];
        }
        if (isset($data['renderDatabase'])) {
            $this->renderDatabase = $data['renderDatabase'];
        }
        return $data;
    }


    protected function render()
    {
        $eloquentClasses = $this->eloquentClasses;
        // Cache In Minutes
        $renderDatabaseArray = Cache::remember('sitec_support_render_database_'.md5(implode('|', $eloquentClasses->values()->all())), 30, function () use ($eloquentClasses) {
            Log::debug(
                'Mount Database -> Renderizando'
            );
            $renderDatabase = (new \Support\Components\Database\Render\Database($eloquentClasses));
            return $renderDatabase->toArray();
        });
        
        // // Persist Models With Errors @todo retirar ignoretedClasses
        // $this->ignoretedClasses = $this->eloquentClasses->diffKeys($renderDatabaseArray["Leitoras"]["displayClasses"]);
        $eloquentClasses = $this->eloquentClasses = collect($renderDatabaseArray["Leitoras"]["displayClasses"]);
        // dd(
        //     'Olaaaa Database Mount',
        //     $eloquentClasses,
        //     $this->ignoretedClasses 
        // );

        $this->renderDatabase = $renderDatabaseArray;
        
        $this->relationships = $eloquentClasses->map(function($eloquentData, $className) use ($renderDatabaseArray) {

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
                return new Relationship($relation);
            }
        });
        


        $this->entitys = $eloquentClasses->reject(function($eloquentData, $className) {
            return $this->eloquentHasError($className);
        })->map(function($eloquentData, $className) use ($renderDatabaseArray) {
            return (new EloquentMount($className, $renderDatabaseArray))->getEntity();
        });
        //     dd(
        //         $this->entitys,
        //     $this->renderDatabase['AplicationTemp']['tempErrorClasses']
        // );
        
        // $databaseEntity = new DatabaseEntity();
        
        // $databaseEntity = new DatabaseEntity();
        // $databaseEntity

    }

    public function getAllEloquentsEntitys()
    {
    //     dd($this->entitys,
    //     $this->renderDatabase['AplicationTemp']['tempErrorClasses']
    // );
        return $this->entitys->toArray();
    }

    public function getEloquentEntity($className)
    {
        $className = $this->returnProcuracaoClasse($className);
        if ($this->eloquentHasError($className)) {
            return false;
        }

        if (!empty($className) && isset($this->entitys->toArray()[$className])) {
            return $this->entitys->toArray()[$className];
        }

        Log::channel('sitec-support')->error(
            'DatabaseMount. Nao encontrado pra classe: '.$className
        );
        // dd(
        //     'Aqui Agora',
        //     $class,
        //     debug_backtrace()
        // );
        return false;
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
