<?php

namespace Support\Services;



class EloquentService
{
    public static function getForClass($className)
    {
        return resolve(DatabaseService::class)->getEloquentService($className);
    }
}
