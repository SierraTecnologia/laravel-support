<?php

namespace Support\Discovers\Analysers;

use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\TableDiff;
use Support\Discovers\Database\Schema\SchemaManager;
use Support\Discovers\Database\Schema\Table;
use Support\Discovers\Database\Types\Type;
use Support\ClassesHelpers\Development\DevDebug;
use Support\ClassesHelpers\Development\HasErrors;
use Illuminate\Database\Eloquent\Relations\Relation;
use ReflectionClass;
use ReflectionMethod;
use Illuminate\Support\Collection;
use SierraTecnologia\Crypto\Services\Crypto;
use Illuminate\Http\Request;
use Support\Discovers\Eloquent\Relationships;
use App;
use Log;
use Artisan;
use Support\Elements\Entities\DataTypes\Varchar;
use Support\Discovers\Eloquent\EloquentColumn;
use Support\Parser\ParseModelClass;
use Symfony\Component\Inflector\Inflector;

use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\DBALException;

class Eloquent
{
    use DevDebug;
    use HasErrors;
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

    protected $relations = false;

    /**
     * NOt Cached
     */
    protected $schemaManagerTable;
    protected $hardParserModelClass;

    /**
     * Construct
     */
    public function __construct($modelClass = false, $render = false)
    {
        if (in_array($modelClass, $this->modelsForDebug)) {
            $this->debug = true;
        }

        if ($this->modelClass = $modelClass && $render) {
            $this->render();
        }

        // dd($this->toArray());
    }

    /**
     * Static functions
     */ 
    public static function makeFromArray($array)
    {
        // @todo
        // return new self($modelClass);
    }
    public static function make($modelClass)
    {
        return new self($modelClass);
    }

    public function getModelClass()
    {
        return $this->modelClass;
    }

    public function getTableName()
    {
        return $this->tableName;
    }


    /**
     * Update the table.
     *
     * @return void
     */
    public function fromArray($data)
    {
        $this->managerFromArray($data['manager']);
        $this->infoFromArray($data['info']);
    }
    /**
     * Update the table.
     *
     * @return void
     */
    public function infoFromArray($data)
    {
        $this->tableName = $data['tableName'];
        $this->columns = $data['columns'];
        $this->indexes = $data['indexes'];
        $this->primaryKey = $data['primaryKey'];
        $this->attributes = $data['attributes'];
        // $this->getRelations() = $data['relations'];
    }

    /**
     * Update the table.
     *
     * @return void
     */
    public function managerFromArray($data)
    {
        // @todo 
        // $manager['modelManager'] = $this->hardParserModelClass->toArray();
        // $manager['tableManager'] = $this->schemaManagerTable->toArray();
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
        if ($this->isError) {
            return false;
        }
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
            $this->hardParserModelClass = new ParseModelClass($this->modelClass);
            $this->tableName = $this->hardParserModelClass->getData('table');
            $this->name = $this->getName();
            $this->indexes = $this->getIndexes();
            $this->columns = $this->getColumns();
            $this->primaryKey = $this->getPrimaryKey();
            $this->relations = $this->getRelations();

            $this->sendToDebug($this->toArray());
        } catch(SchemaException|DBALException $e) {
            // @todo Tratar, Tabela Nao existe
            $this->setError($e->getMessage());
            
        } catch(\Symfony\Component\Debug\Exception\FatalThrowableError $e) {
            $this->setError($e->getMessage());
            // @todo Armazenar Erro em tabela
            // dd($e);
            //@todo fazer aqui
        } catch(\Exception $e) {
            $this->setError($e->getMessage());
            // dd($e);
        } catch(\Throwable $e) {
            $this->setError($e->getMessage());
            // dd($e);
            // @todo Tratar aqui
        }
    }
    /**
     * Trabalhos Leves
     */
    public function getName($plural = false)
    {
        $reflection = new ReflectionClass($this->modelClass);
        $name = $reflection->getShortName();

        // @todo Fazer plural
        if ($plural) {
            $name = Inflector::pluralize($name);
            if (is_array($name)) {
                $name = $name[count($name) - 1];
            }
        }

        return $name;
    }


    /**
     * Trabalhos Pesados
     */
    public function getRelations($key = false)
    {
        if ($key) {
            return (new Relationships($this->modelClass))($key);
        }

        if (!$this->relations) {
            $this->relations = (new Relationships($this->modelClass))($key);
            // $this->setError($this->relations->getError()); @todo PEgar erro do relationsscripts
        }
        // dd($key, (new Relationships($this->modelClass)),(new Relationships($this->modelClass))($key));
        return $this->relations;
    }

    /**
     * Caracteristicas das Tabelas
     */
    public function getPrimaryKey()
    {
        return $this->hardParserModelClass->getPrimaryKey();
    }
    public function getColumnsFillables()
    {

        // Ou Assim
        // // dd(\Schema::getColumnListing($this->modelClass));
        $fillables = collect(App::make($this->modelClass)->getFillable())->map(function ($value) {
            return new EloquentColumn($value, new Varchar, true);
        });

        return $fillables;
    }
    public function getIndexes()
    {
        if (!$this->schemaManagerTable) {
            dd($this->modelClass);
        }
        return $this->schemaManagerTable->getIndexes();
    }





}
