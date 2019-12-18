<?php

namespace Support\Eloquent;

use ErrorException;
use Illuminate\Database\Eloquent\Relations\Relation;
use ReflectionClass;
use ReflectionMethod;
use Illuminate\Support\Collection;
use Log;

class Relationships
{
    private $model;
    private $relationships;

    public function __construct($model) {
        $this->model = new $model;
        $this->relationships = null;
    }

    public function __invoke($key = false)
    {
        if (!$this->relationships) {
            $this->all();
        }

        if ($key) {
            return $this->byKey($key);
        }
        
        return $this->relationships;
    }

    public function all() {

        $this->relationships = new Collection;

        foreach((new ReflectionClass($this->model))->getMethods(ReflectionMethod::IS_PUBLIC) as $method)
        {
            if ($method->class == get_class($this->model)
                && empty($method->getParameters())
                && $method->getName() !== __FUNCTION__
                /* && $method->isFinal() */) // Retirado o lance do method ser final
            {
                try {
                    $return = $method->invoke($this->model);

                    if ($return instanceof Relation)
                    {
                        $ownerKey = null;
                        if ((new ReflectionClass($return))->hasMethod('getOwnerKey'))
                            $ownerKey = $return->getOwnerKey();
                        else
                        {
                            $segments = explode('.', $return->getQualifiedParentKeyName());
                            $ownerKey = $segments[count($segments) - 1];
                        }

                        $tmpReturnReflectionClass = (new ReflectionClass($return));

                        $tmpForeignKey = '';
                        if ($tmpReturnReflectionClass->hasMethod('getForeignKey')) {
                            $tmpForeignKey = $return->getForeignKey();
                        } else if ($tmpReturnReflectionClass->hasMethod('getForeignKeyName')) {
                            $tmpForeignKey = $return->getForeignKeyName();
                        } else {
                            Log::warning('Relação de Tabelas sem Chave Privada');
                        }

                        $rel = new Relationship([
                            'name' => $method->getName(),
                            'type' => $tmpReturnReflectionClass->getShortName(),
                            'model' => (new ReflectionClass($return->getRelated()))->getName(),
                            'foreignKey' => $tmpForeignKey,
                            'ownerKey' => $ownerKey,
                        ]);

                        $this->relationships[$rel->name] = $rel;
                    }
                } catch(ErrorException $e) {}
            }
        }

        return $this->relationships;
    }

    public function byKey($key)
    {
        $relationships = new Collection;

        foreach ($this->relationships as $name => $relationship)
            if ($relationship->type == 'BelongsTo'
                && $relationship->foreignKey == $key)
                $relationships[$name] = $relationship;

        return $relationships;
    }
}