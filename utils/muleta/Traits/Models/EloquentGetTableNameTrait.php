<?php

namespace Muleta\Traits\Models;

trait EloquentGetTableNameTrait
{

    public static function getTableName()
    {
        return ((new self)->getTable());
    }

}