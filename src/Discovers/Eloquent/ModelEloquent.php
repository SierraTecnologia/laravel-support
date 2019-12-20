<?php

namespace Support\Discovers\Eloquent;

use ErrorException;
use Illuminate\Database\Eloquent\Relations\Relation;
use ReflectionClass;
use ReflectionMethod;
use Illuminate\Support\Collection;
use SierraTecnologia\Crypto\Services\Crypto;
use Illuminate\Http\Request;
use Support\Discovers\Eloquent\Relationships;
use App;
use Log;
use Exception;
use Artisan;
use Support\Elements\Entities\DataTypes\Varchar;
use Support\Discovers\Eloquent\EloquentColumn;
use Support\Discovers\Database\Schema\SchemaManager;
use Support\Discovers\Code\ParseModelClass;

class ModelEloquent
{
    protected $schemaManagerTable = false;
    protected $modelClass;

    /**
     * Helpers for Development
     */ 
    public $debug = false;
    public $modelsForDebug = [
        \Population\Models\Identity\Digital\Account::class,
    ];

    /**
     * Construct
     */
    public function __construct($modelClass = false)
    {
        if (in_array($modelClass, $this->modelsForDebug)) {
            dd($modelClass);
            $this->debug = true;
        }

        if ($this->modelClass = $modelClass) {
            $this->renderTableInfos();
        }
    }

    /**
     * Trabalhos Pesados
     */
    public function getRelations($key = false)
    {
        // dd($key, (new Relationships($this->modelClass)),(new Relationships($this->modelClass))($key));
        return (new Relationships($this->modelClass))($key);
    }

    private function renderTableInfos()
    {
        $this->schemaManagerTable = SchemaManager::listTableDetails(
            ParseModelClass::getTableName($this->modelClass)
        );

        // Debug
        $this->sendToDebug([
            $this->getRelations(),
            $this->schemaManagerTable
        ]);
    }

    /**
     * Relações
     */
    public function getAtributes()
    {
        // dd(\Schema::getColumnListing($this->modelClass));
        $fillables = collect(App::make($this->modelClass)->getFillable())->map(function ($value) {
            return new EloquentColumn($value, new Varchar, true);
        });
        return $fillables;
    }

    /**
     * Caracteristicas das Tabelas
     */
    public function getPrimaryKey()
    {
        return ParseModelClass::getPrimaryKey($this->modelClass);
    }
    public function getColumns()
    {
        // dd($this->getAtributes(), $this->schemaManagerTable->getColumns());
        return $this->schemaManagerTable->getColumns();
    }


    /**
     * Helpers for Development
     */ 
    protected function sendToDebug($data)
    {
        if (!$this->debug) {
            return ;
        }

        dd($data);
    }

    /**
     * Static functions
     */ 
    public static function getForModel($modelClass)
    {
        return new self($modelClass);
    }
}
