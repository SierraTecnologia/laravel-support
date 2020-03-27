<?php

namespace Support\Services;

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
use Support\Render\Relationships;
use App;
use Log;
use Artisan;
use Support\Elements\Entities\DataTypes\Varchar;
use Support\Entitys\EloquentColumn;
use Support\Parser\ParseModelClass;
use Symfony\Component\Inflector\Inflector;

use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\DBALException;

class EloquentService
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
    public function __construct($modelClass = false)
    {
        if (in_array($modelClass, $this->modelsForDebug)) {
            $this->debug = true;
        }

        if ($this->modelClass = $modelClass) {
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

    public function getData($indice)
    {
        $array = $this->managerToArray();
        if (isset($this->array[$indice])) {
            return $this->array[$indice];
        }
        $array = $this->infoToArray();
        if (isset($this->array[$indice])) {
            return $this->array[$indice];
        }

        return false;
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
        $this->relations = $data['relations'];
    }

    /**
     * Update the table.
     *
     * @return void
     */
    public function managerFromArray($data)
    {
        // @todo 
        $manager['modelManager'] = $this->hardParserModelClass->toArray();
        $manager['tableManager'] = $this->schemaManagerTable->toArray();
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
        $manager['tableManager'] = $this->getSchemaManagerTable()->toArray();
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
            // $this->renderDatabase(); @todo Removido
            // $this->analisando();

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
    private function renderModel()
    {
        $this->hardParserModelClass = new ParseModelClass($this->modelClass);
        $this->tableName = $this->hardParserModelClass->getData('table');
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
        return $this->hardParserModelClass->getPrimaryKey($this->modelClass);
    }
    public function getColumns()
    {
        // Ou Assim
        // // dd(\Schema::getColumnListing($this->modelClass));
        $fillables = collect($this->getTableDetailsArray())->map(function ($value) {
            return EloquentColumn::returnFromArray($value, $this);
        });

        // dd($fillables);

        return $fillables;
    }
    public function getColumnsForList()
    {
        $fillables = $this->getColumns();

        $fillables = $fillables->reject(function($column) {
            if ($column->getColumnName === 'deleted_at') {
                return false;
            }
            
            return false;
        });

        dd($fillables);

        return $fillables;
    }
    // public function getColumnsArray()
    // {
    //     return $this->schemaManagerTable->getColumns();
    // }
    public function getTableDetailsArray()
    {
        /**
         * ^ Illuminate\Support\Collection {#799 ▼
         *   #items: array:6 [▼
         * id" => array:19 [▶]
         * name" => array:21 [▼
           * name" => "name"
           * type" => "varchar"
           * default" => null
           * notnull" => false
           * length" => 255
           * precision" => 10
           * scale" => 0
           * fixed" => false
           * unsigned" => false
           * autoincrement" => false
           * columnDefinition" => null
           * comment" => null
           * charset" => "utf8mb4"
           * collation" => "utf8mb4_unicode_ci"
           * oldName" => "name"
           * null" => "YES"
           * extra" => ""
           * composite" => false
           * field" => "name"
           * indexes" => []
           * key" => null
          *    ]
         * description" => array:21 [▶]
         * created_at" => array:19 [▼
           * name" => "created_at"
           * type" => "timestamp"
           * default" => null
           * notnull" => false
           * length" => 0
           * precision" => 10
           * scale" => 0
           * fixed" => false
           * unsigned" => false
           * autoincrement" => false
           * columnDefinition" => null
           * comment" => null
           * oldName" => "created_at"
           * null" => "YES"
           * extra" => ""
           * composite" => false
           * field" => "created_at"
           * indexes" => []
           * key" => null
          *    ]
         * updated_at" => array:19 [▶]
         * deleted_at" => array:19 [▶]
          *  ]
         * }
         */
        return SchemaManager::describeTable(
            $this->tableName
        );
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
        return $this->getSchemaManagerTable()->getIndexes();
    }

    private function getSchemaManagerTable()
    {
        if (!$this->schemaManagerTable) {
            $this->schemaManagerTable = SchemaManager::listTableDetails($this->getTableName());
        }
        return $this->schemaManagerTable;
    }







    /**
     * Helpers Generates
     */ 
    public function hasColumn($column)
    {
        // $columns = SchemaManager::listTableColumnNames($this->getTableName());
        // return in_array($column, $columns);

        return $this->getSchemaManagerTable()->hasColumn($column);
    }
    public function columnIsType($columnName, $typeClass)
    {
        $column = SchemaManager::getDoctrineColumn($this->getTableName(), $columnName);
        
        if ($column->getType() instanceof $typeClass) {
            return true;
        }
        return false;

        // $columnArray = [
        //     'name' => '',
        //     'type' => ''
        // ];
        // $columnArray['name'] = $columnName;
        // $column = \Support\Discovers\Database\Schema\Column::make($columnArray, $this->getTableName());
        // dd($column);
        // return $column->columnIsType($columnName, $typeClass);
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
