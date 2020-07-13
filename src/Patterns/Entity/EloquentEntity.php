<?php

namespace Support\Patterns\Entity;

use Support\Contracts\Manager\EntityAbstract;


use Support\Utils\Compare\StringCompare;

use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\TableDiff;
use Support\Components\Database\Schema\SchemaManager;
use Support\Components\Database\Schema\Table;
use Support\Components\Database\Types\Type;
use Illuminate\Database\Eloquent\Relations\Relation;
use ReflectionClass;
use ReflectionMethod;
use Illuminate\Support\Collection;
use SierraTecnologia\Crypto\Services\Crypto;
use Illuminate\Http\Request;
use App;
use Log;
use Artisan;
use Support\Elements\Entities\DataTypes\Varchar;
use Support\Patterns\Entity\EloquentColumnEntity;
use Support\Patterns\Parser\ParseModelClass;
use Symfony\Component\Inflector\Inflector;

use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\DBALException;
use Support\Traits\Coder\GetSetTrait;

class EloquentEntity extends EntityAbstract
{

    /**
     * Atributos
     */
    use GetSetTrait;

    /**
     * modelClass
     *
     * @var    string
     * @getter true
     * @setter false
     */
    protected $modelClass;

    /**
     * primaryKey
     *
     * @var    string
     * @getter true
     * @setter true
     */
    protected $primaryKey;

    /**
     * name
     *
     * @var    string
     * @getter true
     * @setter true
     */
    protected $name;

    /**
     * Coluna a Ser Exibida em Relacionamentos
     *
     * @var    string
     * @getter true
     * @setter true
     */
    protected $displayName;


    /**
     * tablename
     *
     * @var    string
     * @getter true
     * @setter true
     */
    protected $tablename;


    /**
     * indexes
     *
     * @var    array
     * @getter true
     * @setter true
     */
    protected $indexes;

    /**
     * icon
     *
     * @var    string
     * @getter true
     * @setter true
     */
    protected $icon;

    /**
     * groupPackage
     *
     * @var    string
     * @getter true
     * @setter true
     */
    protected $groupPackage;

    /**
     * groupType
     *
     * @var    string
     * @getter true
     * @setter true
     */
    protected $groupType;

    /**
     * historyType
     *
     * @var    string
     * @getter true
     * @setter true
     */
    protected $historyType;

    /**
     * registerType
     *
     * @var    string
     * @getter true
     * @setter true
     */
    protected $registerType;




    /**
     * Dados
     */

    public $data;
    public $dataForColumns; // Array
    public $columns; // Em instancias

    public static $mapper = [
        'icon',
        'dataForColumns',
        'data',
    ];


    /**
     * Colocar isso em um Trait
     */
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
    public function addColumn(EloquentColumnEntity $column)
    {
        $this->columns[] = $column;
    }

    public function getColumns()
    {
        return $this->columns;
    }





    public function getColumnsForList()
    {
        $fillables = $this->getColumns();

        $fillables = $fillables->reject(
            function ($column) {
                if ($column->getColumnName === 'deleted_at') {
                    return false;
                }
            
                return false;
            }
        );

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



}
