<?php

namespace Support\Services;

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

use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\DBALException;

class EloquentService
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

        // dd($this->toArray());
    }

    /**
     * Static functions
     */ 
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
        return $this->hardParserModelClass;
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
        $this->hardParserModelClass = new ParseModelClass($this->modelClass);
        $this->tableName = $this->hardParserModelClass->getData('table');
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
     * Trabalhos Leves
     */
    public function getName($plural = false)
    {
        $reflection = new ReflectionClass($this->modelClass);
        $name = $reflection->getShortName();

        // @todo Fazer plural
        if ($plural) {
            $name .= 's';
        }

        return $name;
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
    public function getColumnsArray()
    {
        return $this->schemaManagerTable->getColumns();
    }
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
        if (!$this->schemaManagerTable) {
            dd($this->modelClass);
        }
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
