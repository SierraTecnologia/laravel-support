<?php

namespace Support\Coder\Cached;

use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Schema\TableDiff;
use Support\Coder\Discovers\Database\Schema\SchemaManager;
use Support\Coder\Discovers\Database\Schema\Table;
use Support\Coder\Discovers\Database\Types\Type;
use Support\ClassesHelpers\Development\DevDebug;
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
use Support\Coder\Parser\ParseModelClass;
use Support\Coder\Cached\EloquentCached;

class EloquentCached
{
    use DevDebug;
    /**
     * Identify
     */
    protected $modelClass;

    /**
     * Cached
     */
    protected $tableName;
    protected $colunasDaTabela;
    protected $columns;
    protected $indexes;
    protected $primaryKey;
    protected $attributes;

    protected $arrayTableClass;

    /**
     * NOt Cached
     */
    protected $schemaManagerTable;
    protected $hardParserModelClass;

    /**
     * Construct
     */
    public function __construct($modelClass = false)
    {
        if (in_array($modelClass, $this->modelsForDebug)) {
            $this->debug = true;
        }

        if ($this->modelClass = $modelClass) {
            $this->render();
        }

    }

    /**
     * Update the table.
     *
     * @return void
     */
    public function toArray()
    {
        $array = [];
        $array['tableName'] = $this->tableName;

        $array['tableManager'] = $this->arrayTableClass;


        $array['colunasDaTabela'] = $this->colunasDaTabela;
        $array['columns'] = $this->columns;
        $array['indexes'] = $this->indexes;
        $array['primaryKey'] = $this->primaryKey;
        $array['attributes'] = $this->attributes;

        return $array;
    }

    /**
     * Update the table.
     *
     * @return void
     */
    public function render()
    {
        try {
            $this->renderModel();
            $this->renderDatabase();
            $this->analisando();

            $this->sendToDebug($this->toArray());
        } catch(\Symfony\Component\Debug\Exception\FatalThrowableError $e) {
            dd($e);
            //@todo fazer aqui
        } catch(\Exception $e) {
            dd($e);
        } catch(\Throwable $e) {
            dd($e);
            // @todo Tratar aqui
        }
    }
    private function renderModel()
    {
        $this->tableName = ParseModelClass::getTableName($this->modelClass);
        $this->hardParserModelClass = new ParseModelClass($this->modelClass);
    }
    private function renderDatabase()
    {
        if (!SchemaManager::tableExists($this->tableName)) {
            throw SchemaException::tableDoesNotExist($this->tableName);
        }

        $this->schemaManagerTable = SchemaManager::listTableDetails(
            $this->tableName
        );

        $this->arrayTableClass = $this->schemaManagerTable->toArray();


        /**
         * Cached
         */
        $this->colunasDaTabela = SchemaManager::describeTable(
            $this->tableName
        );
        $this->indexes = $this->getIndexes();
        $this->columns = $this->getColumns();
        $this->attributes = $this->getAtributes();
        $this->primaryKey = $this->getPrimaryKey();


    }

    private function analisando()
    {






    }


    /**
     * Trabalhos Pesados
     */
    public function getRelations($key = false)
    {
        // dd($key, (new Relationships($this->modelClass)),(new Relationships($this->modelClass))($key));
        return (new Relationships($this->modelClass))($key);
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



}
