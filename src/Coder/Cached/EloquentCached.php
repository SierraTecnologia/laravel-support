<?php

namespace Support\Coder\Cached;

use Doctrine\DBAL\Schema\Column;
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

use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\DBALException;

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

        $array['manager'] = $this->managerToArray();


        $array['info'] = $this->infoToArray();

        return $array;
    }


    /**
     * Update the table.
     *
     * @return void
     */
    public function infoToArray()
    {
        $array = [];
        $array['tableName'] = $this->tableName;

        $array['columnsForList'] = $this->columnsForList;
        $array['columnsForEdit'] = $this->columnsForEdit;
        $array['columns'] = $this->columns;
        $array['indexes'] = $this->indexes;
        $array['primaryKey'] = $this->primaryKey;
        $array['attributes'] = $this->attributes;

        $array['relations'] = $this->getRelations();
        return $array;
    }

    /**
     * Update the table.
     *
     * @return void
     */
    public function managerToArray()
    {
        $manager = [];
        $manager['modelManager'] = $this->hardParserModelClass->toArray();
        $manager['tableManager'] = $this->schemaManagerTable->toArray();
        return $manager;
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
        } catch(SchemaException|DBALException $e) {
            // @todo Tratar, Tabela Nao existe
            Log::error($e->getMessage());
        } catch(\Symfony\Component\Debug\Exception\FatalThrowableError $e) {
            // @todo Armazenar Erro em tabela
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
            throw SchemaException::tableDoesNotExist($this->tableName.' ('.$this->modelClass.')');
        }

        $this->schemaManagerTable = SchemaManager::listTableDetails(
            $this->tableName
        );



        /**
         * Cached
         */
        $this->columnsForList = [];
        $this->columnsForEdit = []; 
        $this->indexes = $this->getIndexes();
        $this->columns = $this->getColumns();
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
     * Caracteristicas das Tabelas
     */
    public function getPrimaryKey()
    {
        return ParseModelClass::getPrimaryKey($this->modelClass);
    }
    public function getColumns()
    {
        // dd($this->getColumns()), $this->schemaManagerTable->getColumns());
        return $this->schemaManagerTable->getColumns();

        // Ou assim
        // SchemaManager::describeTable(
        //     $this->tableName
        // );

        // Ou Assim
        // // dd(\Schema::getColumnListing($this->modelClass));
        // $fillables = collect(App::make($this->modelClass)->getFillable())->map(function ($value) {
        //     return new EloquentColumn($value, new Varchar, true);
        // });
        // return $fillables;
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
