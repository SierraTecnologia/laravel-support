<?php

namespace Support\Services;



class EloquentService
{
    public static function getForClass($className)
    {
        // $databaseService = resolve(DatabaseService::class);
        // return resolve(DatabaseService::class)->getEloquentService($className);
        return resolve(DatabaseService::class)->getEloquentService($className);
    }
}
