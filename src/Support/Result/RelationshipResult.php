<?php

namespace Facilitador\Support\Result;

use Support\Models\Application\DataRelationship;
use Support\Models\Application\DataType;
use Illuminate\Support\Collection;
use Support\Services\ModelService;
use Support\Services\RepositoryService;

class RelationshipResult
{
    public $dataType;
    public $relationShip;
    public $results;
    public $repository;

    public function __construct(DataType $dataType, DataRelationship $relationShip, Collection $results)
    {
        $this->dataType = $dataType;
        $this->relationShip = $relationShip;
        $this->results = $results;
        $this->repository = new RepositoryService(new ModelService($relationShip->model));
    }
}
