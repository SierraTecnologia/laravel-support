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

use Support\Entitys\DatabaseEntity;
use Support\Entitys\EloquentEntity;
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

    protected function getRenderDatabase()
    {
        if (!$this->renderDatabase) {
            $this->renderDatabase = (new \Support\Render\Database($this->eloquentClasses));

        }
        return $this->renderDatabase;
    }


    protected function render()
    {
        $selfInstance = $this;
        // Cache In Minutes
        $value = Cache::remember('sitec_support_'.md5(implode('|', $selfInstance->eloquentClasses->values()->all())), 30, function () use ($selfInstance) {

            $renderDatabase = (new \Support\Render\Database($selfInstance->eloquentClasses));

// dd( $renderDatabase->getEloquentClasses());
            $this->eloquentClasses = $renderDatabase->getEloquentClasses()->map(function($file, $class) {
                return new \EloquentMount($class);
            })->values()->all();

            return $selfInstance->toArray();
        });
        $this->setArray($value);
        
        // $databaseEntity = new DatabaseEntity();
        
        // $databaseEntity = new DatabaseEntity();
        // $databaseEntity

    }



    public function getEloquentService($class)
    {
        $databaseEntity = new EloquentEntity($class, $this->getRenderDatabase());
        return $databaseEntity;
    }

}
