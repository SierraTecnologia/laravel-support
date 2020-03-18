<?php

namespace Support\Services;


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

class DatabaseService
{
    protected $allModels = false;

    protected $composerParser = false;
    protected $configModelsAlias = [];


    protected $renderDatabase = false;

    public function __construct($configModelsAlias, ComposerParser $composerParser)
    {
        $this->configModelsAlias = $configModelsAlias;
        $this->composerParser = $composerParser;
        $this->getRenderDatabase(); // @todo Fazer isso aqui
    }

    public function getAllModels()
    {
        if (!$this->allModels) {
            $models = $this->composerParser->returnClassesByAlias($this->configModelsAlias);
            $this->allModels = $models->reject(function($filePath, $class) {
                return !(new \Support\Discovers\Identificadores\ClasseType($class))->typeIs('model');
            });
        }
        return $this->allModels;
    }

    public function getRenderDatabase()
    {
        if (!$this->renderDatabase) {
            $this->renderDatabase = (new \Support\Mount\DatabaseMount(collect($this->getAllModels())));

        }
        return $this->renderDatabase;
    }


    public function getEloquentService($class)
    {
        return $this->getRenderDatabase()->getEloquentService($class);
    }

    /**
     * Cached
     */

}
