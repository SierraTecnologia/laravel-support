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

use Support\Parser\ComposerParser;

use Support\Elements\Entities\DatabaseEntity;
use Support\Elements\Entities\EloquentEntity;
use Illuminate\Support\Facades\Cache;

class DatabaseMount
{
    protected $eloquentClasses = false;


    protected $renderDatabase = false;


    public function __construct($eloquentClasses)
    {
        $this->eloquentClasses = $eloquentClasses;

        $this->render();
    }

    protected function toArray()
    {
        $data = [];

        $data['eloquentClasses'] = $this->eloquentClasses;
        return $data;
    }


    protected function setArray($data)
    {
        if (isset($data['renderDatabase'])) {
            $this->eloquentClasses = $data['eloquentClasses'];

        }
        return $data;
    }


    protected function render()
    {
        $eloquentClasses = $this->eloquentClasses;
        // Cache In Minutes
        $renderDatabase = Cache::remember('sitec_support_render_database_'.md5(implode('|', $eloquentClasses->values()->all())), 30, function () use ($eloquentClasses) {

            $renderDatabase = (new \Support\Render\Database($eloquentClasses));

            return $renderDatabase->toArray();
        });
        $eloquentClasses = $this->eloquentClasses = collect($renderDatabase["Leitoras"]["displayClasses"]);
        $this->renderDatabase = $renderDatabase;
        $this->entitys = $eloquentClasses->map(function($eloquentData, $className) use ($renderDatabase) {
            return (new EloquentMount($className, $renderDatabase))->getEntity();
        });
        
        // $databaseEntity = new DatabaseEntity();
        
        // $databaseEntity = new DatabaseEntity();
        // $databaseEntity

    }



    public function getEloquentEntity($class)
    {
        if (!empty($class) && isset($this->entitys->toArray()[$class])) {
            return $this->entitys->toArray()[$class];
        }

        return false;
    }

}
