<?php

namespace Support\Coder\Discovers\Eloquent;

use ErrorException;
use Illuminate\Database\Eloquent\Relations\Relation;
use ReflectionClass;
use ReflectionMethod;
use Symfony\Component\Inflector\Inflector;
use Illuminate\Support\Collection;

class Database
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

    public function getName()
    {
        return $this->name;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getModel()
    {
        return $this->model;
    }




    protected function getListTables()
    {
        $keys = [];
        $listTables = \Support\Coder\Discovers\Database\Schema\SchemaManager::listTables();
        foreach ($listTables as $listTable){
            if (!empty($indexes = $listTable->exportIndexesToArray())) {
                foreach ($indexes as $index) {
                    if ($index['type'] == 'PRIMARY') {
                        $keys[$listTable->getName().'_'.$index['columns'][0]] = [
                            'name' => $listTable->getName(),
                            'key' => $index['columns'][0],
                            'label' => 'name'
                        ];
                    }
                }
            }
        }

        return $keys;
    }
}
