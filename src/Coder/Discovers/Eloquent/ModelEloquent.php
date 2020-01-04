<?php

namespace Support\Coder\Discovers\Eloquent;

use ErrorException;
use Illuminate\Database\Eloquent\Relations\Relation;
use ReflectionClass;
use ReflectionMethod;
use Illuminate\Support\Collection;
use SierraTecnologia\Crypto\Services\Crypto;
use Illuminate\Http\Request;
use Support\Coder\Discovers\Eloquent\Relationships;
use App;
use Log;
use Exception;
use Artisan;
use Support\Elements\Entities\DataTypes\Varchar;
use Support\Coder\Discovers\Eloquent\EloquentColumn;
use Support\Coder\Discovers\Database\Schema\SchemaManager;
use Support\Coder\Parser\ParseModelClass;

class ModelEloquent
{
    protected $schemaManagerTable = false;
    protected $modelClass;

    /**
     * Helpers for Development @todo Tirar daqui
     */ 
    public $debug = false;
    public $modelsForDebug = [
        // \Population\Models\Identity\Digital\Account::class,
        // \Population\Models\Identity\Digital\Email::class,
    ];

    /**
     * Construct
     */
    public function __construct($modelClass = false)
    {
        if (in_array($modelClass, $this->modelsForDebug)) {
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
        $describeTable = SchemaManager::describeTable(
            ParseModelClass::getTableName($this->modelClass)
        );

        // Debug
        $this->sendToDebug([
            // $describeTable,
            $this->getRelations(),
            $this->schemaManagerTable,
            // $this->schemaManagerTable->getIndexes()
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
    public function getIndexes()
    {
        return $this->schemaManagerTable->getIndexes();
    }







    /**
     * Helpers Generates
     */ 
    public function hasColumn($columns)
    {
        return $this->schemaManagerTable->hasColumn($columns);
    }
    public function columnIsType($columnName, $typeClass)
    {
        return $this->schemaManagerTable->columnIsType($columnName, $typeClass);
    }







    /**
     * Helpers Generates
     */ 
    public function generateWhere($columns, $data)
    {
        $where = [];
        foreach ($columns as $column) {
            if (isset($data[$column]) && !empty($data[$column])) {
                $where[$column] = $data[$column];
                // @todo resolver
                // $where[$column] = static::cleanCodeSlug($data[$column]);
            }
        }
        return $where;
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
    public static function make($modelClass)
    {
        return new self($modelClass);
    }
}
