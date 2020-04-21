<?php

namespace Support\Elements\Entities;

use ErrorException;
use Illuminate\Database\Eloquent\Relations\Relation;
use ReflectionClass;
use ReflectionMethod;
use Illuminate\Support\Collection;
use Symfony\Component\Inflector\Inflector;

class Relationship
{


    public $origin_table_name;
    public $origin_table_class;
    public $origin_foreignKey;

    public $related_table_name;
    public $related_table_class;
    public $related_foreignKey;

    // Morph
    public $morph_id;
    public $morph_type;
    public $is_inverse;

    // Others Values
    public $pivot;

    public $name;
    public $type;
    public $model;
    public $foreignKey;
    public $ownerKey;


    protected $filliables = [

        'origin_table_name',
        'origin_table_class',
        'origin_foreignKey',
    
        'related_table_name',
        'related_table_class',
        'related_foreignKey',
    
        // Morph
        'morph_id',
        'morph_type',
        'is_inverse',
    
        // Others Values
        'pivot',
    
        'name',
        'type',
        'model',
        'foreignKey',
        'ownerKey',
    ];


    /**
     * 
                            'origin_table_name' => $this->model,
                            'origin_table_class' => $this->model,
                            'origin_foreignKey' => $tmpForeignKey,

                            'related_table_name' => (new ReflectionClass($return->getRelated()))->getName(),
                            'related_table_class' => $return->getRelationName(),
                            'related_foreignKey' => $return->getRelatedKeyName(),

                            // Morph
                            'morph_id' => $return->getMorphType(),
                            'morph_type' => $return->getMorphType(),
                            'is_inverse' => $return->getInverse(),

                            // Others Values
                            'pivot' => false,

                            // Old
                            'name' => $method->getName(),
                            'type' => $tmpReturnReflectionClass->getShortName(),
                            'model' => (new ReflectionClass($return->getRelated()))->getName(),
                            'ownerKey' => $ownerKey,
                            'foreignKey' => $tmpForeignKey,
     */
    public function __construct($relationship = [])
    {
        if ($relationship)
        {
            foreach ($this->filliables as $filliable) {
                if (isset($relationship[$filliable])) {
                    $this->{$filliable} = $relationship[$filliable];
                }
            }
            // $this->origin_table_name = $relationship['origin_table_name'];
            // $this->origin_table_class = $relationship['origin_table_class'];
            // $this->origin_foreignKey = $relationship['origin_foreignKey'];

            // $this->related_table_name = $relationship['related_table_name'];
            // $this->related_table_class = $relationship['related_table_class'];
            // $this->related_foreignKey = $relationship['related_foreignKey'];

            // // Morph
            // $this->morph_type = $relationship['morph_type'];
            // $this->morph_type = $relationship['morph_type'];
            // $this->is_inverse = $relationship['is_inverse'];

            // // Others Values
            // $this->pivot = $relationship['pivot'];


            // // Old
            // $this->name = $relationship['name'];
            // $this->type = $relationship['type'];
            // $this->model = $relationship['model'];
            // $this->foreignKey = $relationship['foreignKey'];
            // $this->ownerKey = $relationship['ownerKey'];
        }
    }

    public function persist()
    {
        if (!$model = DataRelationship::find($this->getCodeName())) {
            $model = new DataRelationship();
            $model->code = $this->getCodeName();
            foreach ($this->filliables as $filliable) {
                $model->{$filliable} = $this->{$filliable};
            }
            $model->save();
        }
        return $model;
    }

    public function toArray()
    {
        $relationship = [];

        foreach ($this->filliables as $filliable) {
            $relationship[$filliable] = $this->{$filliable};
        }
        // $relationship['name'] = $this->getName();
        // $relationship['type'] = $this->getType();
        // $relationship['model'] = $this->model;
        // $relationship['foreignKey'] = $this->foreignKey;
        // $relationship['ownerKey'] = $this->ownerKey;
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
     * 
     * 
     * 
     * 
     * // Outros @todo
     * hasOne one to one
     * hasMany one to Many
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


    protected function getCodeName()
    {

        $code = 
            Inflector::singularize($this->origin_table_name).'_'.
            $this->type.'_'.
            Inflector::singularize($this->related_table_name);


        return $code;
    }
}
