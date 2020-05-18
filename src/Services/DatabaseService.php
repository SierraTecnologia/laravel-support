<?php

namespace Support\Services;

use Support\Components\Database\Mount\DatabaseMount;
use Support\Elements\Entities\EloquentEntity;
use Support\Components\Coders\Parser\ComposerParser;
use Illuminate\Support\Collection;
use Support\Exceptions\Coder\EloquentNotExistException;

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
        $this->getDatabaseMount(); // @todo Remover isso aqui e ver se funciona
    }

    public function getAllEloquentsEntitys(): array
    {
        $this->getDatabaseMount();
        return $this->eloquentEntitysLoaders;
        // return $this->getDatabaseMount()->getAllEloquentsEntitys();
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
            throw new EloquentNotExistException($className);
        }
        return $this->eloquentEntitysLoaders[$className];
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
    }

    public function renderEloquentEntityFromClassName(string $className): EloquentEntity
    {
        if (!$this->hasEloquentEntityFromClassName($className)) {
            $this->registerEloquentEntity(
                $this->getDatabaseMount()->getEloquentEntityFromClassName($className)
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
    private function getDatabaseMount(): DatabaseMount
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
