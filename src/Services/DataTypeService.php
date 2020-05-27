<?php

namespace Support\Services;

use Support\Elements\Entities\EloquentEntity;


class DataTypeService
{
    protected $eloquentEntity;

    // public function __construct(DatabaseService $databaseService, $class)
    public function __construct(string $className)
    {
        $this->eloquentEntity = resolve(ApplicationService::class)->forceGetEloquentEntityFromClassName($className);
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
