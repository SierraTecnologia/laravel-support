<?php

declare(strict_types=1);


namespace Support\Mount;


use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Support\Result\RelationshipResult;
use SierraTecnologia\Crypto\Services\Crypto;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Support\Discovers\Database\DatabaseUpdater;
use Support\Discovers\Database\Schema\Column;
use Support\Discovers\Database\Schema\Identifier;
use Support\Discovers\Database\Schema\SchemaManager;
use Support\Discovers\Database\Schema\Table;
use Support\Discovers\Database\Types\Type;
use Support\Parser\ParseModelClass;
use Support\ClassesHelpers\Extratores\StringExtractor;
use Support\ClassesHelpers\Extratores\ArrayExtractor;
use Support\Parser\ComposerParser;

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
    protected $errorsInModels = [];


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
        $data['errorsInModels'] = $this->errorsInModels;
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
        $renderDatabase = Cache::remember('sitec_support_render_database_'.md5(implode('|', $eloquentClasses->values()->all())), 30, function () use ($eloquentClasses) {
            Log::debug(
                'Mount Database -> Renderizando'
            );
            $renderDatabase = (new \Support\Render\Database($eloquentClasses));

            return $renderDatabase->toArray();
        });
        
        // Persist Models With Errors
        $this->errorsInModels = $this->eloquentClasses->diffKeys($renderDatabase["Leitoras"]["displayClasses"]);

        $eloquentClasses = $this->eloquentClasses = collect($renderDatabase["Leitoras"]["displayClasses"]);
        $this->renderDatabase = $renderDatabase;
        
        $this->relationships = $eloquentClasses->map(function($eloquentData, $className) use ($renderDatabase) {
            foreach ($eloquentData['relations'] as $relation) {
                if (!isset($relation['origin_table_name']) || empty($relation['origin_table_name'])) {
                    $relation['origin_table_name'] = $renderDatabase["Leitoras"]["displayClasses"][$relation['origin_table_class']]["tableName"];
                }
                if (!isset($relation['related_table_name']) || empty($relation['related_table_name'])) {
                    $relation['related_table_name'] = ArrayExtractor::returnNameIfNotExistInArray(
                        $relation['related_table_class'],
                        $renderDatabase,
                        '["Leitoras"]["displayClasses"][{{index}}]["tableName"]'
                    );
                }
                return new Relationship($relation);
            }
        });
        


        $this->entitys = $eloquentClasses->map(function($eloquentData, $className) use ($renderDatabase) {
            return (new EloquentMount($className, $renderDatabase))->getEntity();
        });
        
        // $databaseEntity = new DatabaseEntity();
        
        // $databaseEntity = new DatabaseEntity();
        // $databaseEntity

    }

    public function getAllEloquentsEntitys()
    {
        return $this->entitys->toArray();
    }

    public function getEloquentEntity($class)
    {
        if (!empty($class) && isset($this->entitys->toArray()[$class])) {
            return $this->entitys->toArray()[$class];
        }

        dd(
            'Aqui Agora'
        );
        return false;
    }

}
