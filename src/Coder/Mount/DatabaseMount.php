<?php

declare(strict_types=1);


namespace Support\Coder\Mount;


use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Support\Result\RelationshipResult;
use SierraTecnologia\Crypto\Services\Crypto;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Support\Coder\Discovers\Database\DatabaseUpdater;
use Support\Coder\Discovers\Database\Schema\Column;
use Support\Coder\Discovers\Database\Schema\Identifier;
use Support\Coder\Discovers\Database\Schema\SchemaManager;
use Support\Coder\Discovers\Database\Schema\Table;
use Support\Coder\Discovers\Database\Types\Type;
use Support\Coder\Parser\ParseModelClass;

use Support\Coder\Parser\ComposerParser;

use Support\Coder\Entitys\DatabaseEntity;
use Support\Coder\Entitys\EloquentEntity;

class DatabaseMount
{
    protected $eloquentClasses = false;


    protected $renderDatabase = false;


    public function __construct($eloquentClasses)
    {
        $this->eloquentClasses = $eloquentClasses;

        $this->getRenderDatabase();

        $this->render();
    }

    protected function getRenderDatabase()
    {
        if (!$this->renderDatabase) {
            $this->renderDatabase = (new \Support\Coder\Render\Database($this->eloquentClasses));

        }
        return $this->renderDatabase;
    }


    protected function render()
    {
        
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
