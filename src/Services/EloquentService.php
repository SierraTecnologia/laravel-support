<?php

namespace Support\Services;



class EloquentService
{
    protected $eloquentEntity;

    // public function __construct(DatabaseService $databaseService, $class)
    public function __construct($class)
    {
        $databaseService = resolve(DatabaseService::class);
        if (!$databaseService->hasEloquentEntityFromClassName($class)) {
            $this->eloquentEntity = $databaseService->renderEloquentEntityFromClassName($class);
        }
        $this->eloquentEntity = $databaseService->getEloquentEntityFromClassName($class);
    }

    public function __invoke()
    {
        return $this->eloquentEntity;
    }

    public static function getEloquentEntityFromClassName($className)
    {
        // $databaseService = resolve(DatabaseService::class);
        // return resolve(DatabaseService::class)->getEloquentEntityFromClassName($className);
        return (new self($className))();
    }
}
