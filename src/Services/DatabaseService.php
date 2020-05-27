<?php

namespace Support\Services;

use Support\Components\Database\Mount\DatabaseMount;
use Support\Elements\Entities\EloquentEntity;
use Support\Patterns\Parser\ComposerParser;
use Illuminate\Support\Collection;
use Support\Exceptions\Coder\EloquentNotExistException;
use Support\Exceptions\Coder\EloquentHasErrorException;

class DatabaseService
{
    protected $modelsFindInAlias = false;

    protected $composerParser = false;

    protected $eloquentEntitysLoaders = [];


    protected $databaseMount = false;

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
        $className = $this->returnProcuracaoClasse($className);
        return isset($this->eloquentEntitysLoaders[$className]);
    }

    public function getEloquentEntityFromClassName($className): EloquentEntity
    {
        if (!$this->hasEloquentEntityFromClassName($className)) {
            if (!$this->eloquentHasError($className)) {
                throw new EloquentNotExistException($className);
            }
            throw new EloquentHasErrorException($className, $this->getEloquentError($className));
        }
        $className = $this->returnProcuracaoClasse($className);
        return $this->eloquentEntitysLoaders[$className];
    }

    public function forceGetEloquentEntityFromClassName($className): EloquentEntity
    {
        if ($this->hasEloquentEntityFromClassName($className)) {
            return $this->getEloquentEntityFromClassName($className);
        }
        return $this->renderEloquentEntityFromClassName($className);
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
            if ($this->eloquentHasError($className)) {
                throw new EloquentHasErrorException($className, $this->getEloquentError($className));
            }
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


    /**
     * FROM MOUNTS
     */
    private function getDatabaseMount(): DatabaseMount
    {
        if (!$this->databaseMount) {
            $this->databaseMount = (new DatabaseMount(
                collect($this->extractAllModelsFromComposerWithNamespaceAlias())
            ));
            $this->registerManyEloquentEntity($this->databaseMount->getAllEloquentsEntitys());
        }
        return $this->databaseMount;
    }
    private function eloquentHasError(string $className): string
    {
        return $this->getDatabaseMount()->eloquentHasError($className);
    }
    private function getEloquentError(string $className): string
    {
        return $this->getDatabaseMount()->getEloquentError($className);
    }
    private function returnProcuracaoClasse(string $className): string
    {
        return $this->getDatabaseMount()->returnProcuracaoClasse($className);
    } 

}
