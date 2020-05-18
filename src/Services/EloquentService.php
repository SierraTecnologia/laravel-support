<?php

namespace Support\Services;

use Support\Elements\Entities\EloquentEntity;


class EloquentService
{
    protected $eloquentEntity;

    // public function __construct(DatabaseService $databaseService, $class)
    public function __construct(string $className)
    {
        $databaseService = resolve(DatabaseService::class);
        if (!$databaseService->hasEloquentEntityFromClassName($className)) {
            $this->eloquentEntity = $databaseService->renderEloquentEntityFromClassName($className);
            return ;
        }
        $this->eloquentEntity = $databaseService->getEloquentEntityFromClassName($className);
    }

    public function __invoke(): EloquentEntity
    {
        return $this->eloquentEntity;
    }

    public static function getEloquentEntityFromClassName(string $className): EloquentEntity
    {
        return (new self($className))();
    }
}
