<?php

namespace Support\Elements\Entities;

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
use Support\Elements\Entities\EloquentColumn;
use Support\Parser\ParseModelClass;
use Symfony\Component\Inflector\Inflector;

use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\DBALException;

class EloquentEntity
{
    use DevDebug;
    use HasErrors;

    /**
     * Identify
     */
    protected $modelClass;

    /**
     * Dados
     */
    protected $name;
    protected $icon;
    protected $indexes;
    protected $group = 'other';
    protected $tablename;
    protected $primaryKey;

    public $data;
    public $dataForColumns; // Array
    public $columns; // Em instancias

    /**
     * Construct
     */
    public function __construct($modelClass = false)
    {
        $this->modelClass = $modelClass;
    }

    /**
     * Caracteristicas das Tabelas
     */
    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }
    public function setPrimaryKey($primaryKey)
    {
        return $this->primaryKey = $primaryKey;
    }


    //@todo fazer plural
    public function getName($plural = false)
    {
        return $this->name;
    }
    public function setName($name)
    {
        return $this->name = $name;
    }


    public function getTablename()
    {
        return $this->tablename;
    }
    public function setTablename($tablename)
    {
        return $this->tablename = $tablename;
    }

    public function getIndexes()
    {
        return $this->indexes;
    }
    public function setIndexes($indexes)
    {
        return $this->indexes = $indexes;
    }


    public function getData($indexe = false)
    {
        if (!$indexe || empty($indexe)) {
            return $this->data;
        }
        if (!isset($this->data[$indexe])) {
            return false;
        }
        return $this->data[$indexe];
    }
    public function setData($data)
    {
        return $this->data = $data;
    }

    public function getDataForColumns($indexe = false)
    {
        if (!$indexe || empty($indexe)) {
            return $this->dataForColumns;
        }
        if (!isset($this->dataForColumns[$indexe])) {
            return false;
        }
        return $this->dataForColumns[$indexe];
    }
    public function setDataForColumns($dataForColumns)
    {
        return $this->dataForColumns = $dataForColumns;
    }

    /**
     */
    public function addColumn(EloquentColumn $column)
    {
        $this->columns[] = $column;
    }

    public function getColumns()
    {
        return $this->columns;
    }






    public function getModelClass()
    {

        return $this->modelClass;
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
    // public function getTableDetailsArray()
    // {
    //     /**
    //      * ^ Illuminate\Support\Collection {#799 ▼
    //      *   #items: array:6 [▼
    //      * id" => array:19 [▶]
    //      * name" => array:21 [▼
    //        * name" => "name"
    //        * type" => "varchar"
    //        * default" => null
    //        * notnull" => false
    //        * length" => 255
    //        * precision" => 10
    //        * scale" => 0
    //        * fixed" => false
    //        * unsigned" => false
    //        * autoincrement" => false
    //        * columnDefinition" => null
    //        * comment" => null
    //        * charset" => "utf8mb4"
    //        * collation" => "utf8mb4_unicode_ci"
    //        * oldName" => "name"
    //        * null" => "YES"
    //        * extra" => ""
    //        * composite" => false
    //        * field" => "name"
    //        * indexes" => []
    //        * key" => null
    //       *    ]
    //      * description" => array:21 [▶]
    //      * created_at" => array:19 [▼
    //        * name" => "created_at"
    //        * type" => "timestamp"
    //        * default" => null
    //        * notnull" => false
    //        * length" => 0
    //        * precision" => 10
    //        * scale" => 0
    //        * fixed" => false
    //        * unsigned" => false
    //        * autoincrement" => false
    //        * columnDefinition" => null
    //        * comment" => null
    //        * oldName" => "created_at"
    //        * null" => "YES"
    //        * extra" => ""
    //        * composite" => false
    //        * field" => "created_at"
    //        * indexes" => []
    //        * key" => null
    //       *    ]
    //      * updated_at" => array:19 [▶]
    //      * deleted_at" => array:19 [▶]
    //       *  ]
    //      * }
    //      */
    //     return SchemaManager::describeTable(
    //         $this->tableName
    //     );
    // }
    // public function getColumnsFillables()
    // {

    //     // Ou Assim
    //     // // dd(\Schema::getColumnListing($this->modelClass));
    //     $fillables = collect(App::make($this->modelClass)->getFillable())->map(function ($value) {
    //         return new EloquentColumn($value, new Varchar, true);
    //     });

    //     return $fillables;
    // }

    // private function getSchemaManagerTable()
    // {
    //     if (!$this->schemaManagerTable) {
    //         $this->schemaManagerTable = SchemaManager::listTableDetails($this->getTableName());
    //     }
    //     return $this->schemaManagerTable;
    // }







    // /**
    //  * Helpers Generates
    //  */ 
    public function hasColumn($column)
    {
        return isset($this->dataForColumns[$column]);
    }
    public function columnIsType($columnName, $typeClass)
    {
        if (!isset($this->dataForColumns[$columnName])) {
            return false;
        }

        dd(
            $this->dataForColumns[$columnName],
            $typeClass
        );

        // $column = SchemaManager::getDoctrineColumn($this->getTableName(), $columnName);
        
        // if ($column->getType() instanceof $typeClass) {
        //     return true;
        // }
        // return false;

        // // $columnArray = [
        // //     'name' => '',
        // //     'type' => ''
        // // ];
        // // $columnArray['name'] = $columnName;
        // // $column = \Support\Discovers\Database\Schema\Column::make($columnArray, $this->getTableName());
        // // dd($column);
        // // return $column->columnIsType($columnName, $typeClass);
    }




    public function getGroup()
    {
        return $this->group;
    }
    public function setGroup($group)
    {
        return $this->group = $group;
    }
    public function getIcon()
    {
        return $this->icon;
    }
    public function setIcon($icon)
    {
        return $this->icon = $icon;
    }

}
