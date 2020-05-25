<?php

namespace Support\Services;

use Support\Components\Database\Mount\DatabaseMount;
use Support\Elements\Entities\EloquentEntity;
use Support\Components\Coders\Parser\ComposerParser;
use Illuminate\Support\Collection;
use Support\Exceptions\Coder\EloquentNotExistException;
use Support\Exceptions\Coder\EloquentHasErrorException;

class SystemService
{
    protected $modelsFindInAlias = false;

    protected $composerParser = false;

    protected $eloquentEntitysLoaders = [];


    protected $databaseMount = false;

    public function __construct($configModelsAlias, ComposerParser $composerParser)
    {
        $this->configModelsAlias = $configModelsAlias;
        $this->composerParser = $composerParser;
        // $this->getDatabaseMount(); // @todo Remover isso aqui e ver se funciona
    }
    public function render()
    {
        $systemRepository = resolve(\Support\Repositories\SystemRepository::class);

        $result = $systemRepository->findByType(\Support\Patterns\Entity\DatabaseParser::class);

        dd(
            $result
        );
        // return $listTables = (new \Support\Patterns\Parser\DatabaseParser())();
        return $listTables = (new \Support\Patterns\Parser\DatabaseParser())();
    }

    public function getEntity($entityClass)
    {
        $systemRepository = resolve(\Support\Repositories\SystemRepository::class);

        return $systemRepository->findByType($entityClass);
    }

    /**
     * @todo isso repete deve ter um contrato compartilhado com repository
     */
    public function mapper()
    {
        return $this;
    }

    public function fixes()
    {
        return $this;
    }

    protected function renderTables()
    {
        $listTables = (new \Support\Patterns\Parser\DatabaseParser())();
        $tableBuilder = new \Support\Patterns\Builder\TablesBuilder($listTables);

        $this->tempAppTablesWithNotPrimaryKey = $tableBuilder->getRelationTables();
        $this->displayTables = $tableBuilder->getTables();
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
}
