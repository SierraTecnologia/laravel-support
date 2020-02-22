<?php

namespace Support\Coder\Entitys;

use ErrorException;
use Illuminate\Database\Eloquent\Relations\Relation;
use ReflectionClass;
use ReflectionMethod;
use Illuminate\Support\Collection;

class Relationship
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

    public function toArray()
    {
        $relationship = [];
        $relationship['name'] = $this->name;
        $relationship['type'] = $this->type;
        $relationship['model'] = $this->model;
        $relationship['foreignKey'] = $this->foreignKey;
        $relationship['ownerKey'] = $this->ownerKey;
        return $relationship;
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

    /**
     * User hasMany Phones (One to Many)
     * Phone belongsTo User (Many to One) (Inverso do de cima)
     * 
     * belongsToMany (Many to Many) (Inverso é igual)
     * 
     * morphMany
     * morphTo
     * 
     * morphedByMany (O modelo possui a tabela taggables)
     * morphToMany   (nao possui a tabela taggables)
     */
    public static function isInvertedRelation($relation)
    {
        if ($relation == 'BelongsTo') {
            return true;
        }
        if ($relation == 'MorphTo') {
            return true;
        }
        if ($relation == 'MorphToMany') {
            return true;
        }

        return false;
    }
    public static function getInvertedRelation($relation)
    {
        if ($relation == 'BelongsTo') {
            return 'HasMany';
        }
        if ($relation == 'MorphTo') {
            return 'MorphMany';
        }
        if ($relation == 'MorphToMany') {
            return 'MorphedByMany';
        }

        return 'BelongsToMany';
    }
}
