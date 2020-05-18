<?php

namespace Support\Services;

use Log;
use Illuminate\Http\Request;
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
use Support\Components\Database\Mount\DatabaseMount;
use Exception;
use Support\Elements\Entities\EloquentEntity;
use Support\Components\Coders\Parser\ComposerParser;
use Illuminate\Support\Collection;

class DatabaseService
{
    protected $modelsFindInAlias = false;

    protected $composerParser = false;

    protected $eloquentEntitysLoaders = [];


    protected $renderDatabase = false;

    public function __construct($configModelsAlias, ComposerParser $composerParser)
    {
        $this->configModelsAlias = $configModelsAlias;
        $this->composerParser = $composerParser;
        $this->getRenderDatabase(); // @todo Fazer isso aqui
    }

    public function getAllEloquentsEntitys(): array
    {
        $this->getRenderDatabase();
        return $this->eloquentEntitysLoaders;
        // return $this->getRenderDatabase()->getAllEloquentsEntitys();
    }

    /**
     * Novos
     */


    public function hasEloquentEntityFromClassName($className): bool
    {
        return isset($this->eloquentEntitysLoaders[$className]);
    }

    public function getEloquentEntityFromClassName($className): EloquentEntity
    {
        if (!$this->hasEloquentEntityFromClassName($className)) {
            throw new EloquentNotExistException;
        }
        return $this->eloquentEntitysLoaders[$className];
        // return $this->getRenderDatabase()->getEloquentEntity($class);
    }


    public function registerManyEloquentEntity(array $eloquentEntitys)
    {
        foreach ($eloquentEntitys as $eloquentEntity) {
            $this->registerEloquentEntity($eloquentEntity);
        }

    }

    public function registerEloquentEntity(EloquentEntity $eloquentEntity)
    {
        if ($this->hasEloquentEntityFromClassName($eloquentEntity->getModelClass())) {
            return false;
        }
        return $this->eloquentEntitysLoaders[$eloquentEntity->getModelClass()] = $eloquentEntity;
        // return $this->getRenderDatabase()->getEloquentEntity($class);
    }

    public function renderEloquentEntityFromClassName(string $className): EloquentEntity
    {
        if (!$this->hasEloquentEntityFromClassName($className)) {
            $this->registerEloquentEntity(
                $this->getRenderDatabase()->getEloquentEntity($class)
            );
        }

        return $this->getEloquentEntityFromClassName($className);
    }


    /**
     * Privados
     */

    private function extractAllModelsFromComposerWithNamespaceAlias(): Collection
    {
        if (!$this->modelsFindInAlias) {
            $this->modelsFindInAlias = $this->composerParser->returnClassesByAlias($this->configModelsAlias);
        }
        return $this->modelsFindInAlias;
    }
    private function getRenderDatabase(): DatabaseMount
    {
        if (!$this->renderDatabase) {
            $this->renderDatabase = (new DatabaseMount(
                collect($this->extractAllModelsFromComposerWithNamespaceAlias())
            ));
            $this->registerManyEloquentEntity($this->renderDatabase->getAllEloquentsEntitys());
        }
        return $this->renderDatabase;
    }

}
