<?php

namespace Support\Eloquent;

use ErrorException;
use Illuminate\Database\Eloquent\Relations\Relation;
use ReflectionClass;
use ReflectionMethod;
use Illuminate\Support\Collection;

class ModelEloquent
{
    public $name;
    public $type;
    public $model;
    public $foreignKey;
    public $ownerKey;

    public function __construct($relationship = [])
    {
        if ($relationship)
        {
            $this->name = $relationship['name'];
            $this->type = $relationship['type'];
            $this->model = $relationship['model'];
            $this->foreignKey = $relationship['foreignKey'];
            $this->ownerKey = $relationship['ownerKey'];
        }
    }
}
