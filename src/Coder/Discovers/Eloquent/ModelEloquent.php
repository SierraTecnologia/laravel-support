<?php

namespace Support\Coder\Discovers\Eloquent;

use Illuminate\Database\Eloquent\Relations\Relation;
use ReflectionClass;
use ReflectionMethod;
use Illuminate\Support\Collection;
use SierraTecnologia\Crypto\Services\Crypto;
use Illuminate\Http\Request;
use Support\Coder\Discovers\Eloquent\Relationships;
use App;
use Log;
use Artisan;
use Support\Elements\Entities\DataTypes\Varchar;
use Support\Coder\Discovers\Eloquent\EloquentColumn;
use Support\Coder\Discovers\Database\Schema\SchemaManager;
use Support\Coder\Parser\ParseModelClass;
use Support\ClassesHelpers\Development\DevDebug;
use Support\Coder\Cached\EloquentCached;

class ModelEloquent
{
    use DevDebug;
    
    protected $eloquentCached = false;
    protected $modelClass;

    /**
     * Construct
     */
    public function __construct($modelClass = false)
    {
        if (in_array($modelClass, $this->modelsForDebug)) {
            $this->debug = true;
        }

        if ($this->modelClass = $modelClass) {
            $this->eloquentCached = new EloquentCached($modelClass);
        }
    }




    /**
     * Static functions
     */ 
    public static function make($modelClass)
    {
        return new self($modelClass);
    }



    /**********************************************************
     *********************************************************
     * Via Eloquent
     *********************************************************
     *********************************************************
     */
    public function getRelations($key = false)
    {
        return $this->getEloquentCached()->getRelations($key);
    }

    /**
     * Caracteristicas das Tabelas
     */
    public function getPrimaryKey()
    {
        return $this->getEloquentCached()->getPrimaryKey();
    }
    public function getColumns()
    {
        return $this->getEloquentCached()->getColumns();
    }
    public function getIndexes()
    {
        return $this->getEloquentCached()->getIndexes();
    }







    /**
     * Helpers Generates
     */ 
    public function hasColumn($columns)
    {
        return $this->getEloquentCached()->hasColumn($columns);
    }
    public function columnIsType($columnName, $typeClass)
    {
        return $this->getEloquentCached()->columnIsType($columnName, $typeClass);
    }







    /**
     * Helpers Generates
     */ 
    public function generateWhere($columns, $data)
    {
        return $this->getEloquentCached()->generateWhere($columns, $data);
    }

}
