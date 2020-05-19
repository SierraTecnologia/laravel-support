<?php

namespace Support\Services;

use Support\Elements\Entities\EloquentEntity;


class EloquentService
{
    protected $eloquentEntity;

    // public function __construct(DatabaseService $databaseService, $class)
    public function __construct(string $className)
    {
        $this->eloquentEntity = resolve(DatabaseService::class)->forceGetEloquentEntityFromClassName($className);
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
