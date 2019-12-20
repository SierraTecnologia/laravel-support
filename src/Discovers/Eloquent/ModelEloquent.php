<?php

namespace Support\Discovers\Eloquent;

use ErrorException;
use Illuminate\Database\Eloquent\Relations\Relation;
use ReflectionClass;
use ReflectionMethod;
use Illuminate\Support\Collection;

class ModelEloquent
{//@todo Fazer
    public function __construct()
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
