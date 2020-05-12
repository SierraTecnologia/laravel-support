<?php

namespace Support\Components\Database\Render;

use Support\Elements\Entities\Relationship;
use Illuminate\Support\Collection;
use Facilitador\Services\ModelService;
use Facilitador\Services\RepositoryService;
use Support\Elements\Entities\DataType;
use Illuminate\Database\Eloquent\Model;

use Support\Elements\Entities\DataTypes\Varchar;
use Symfony\Component\Inflector\Inflector;
use Support\Services\EloquentService;

class Columns
{
    public $displayName = false;
    public $displayColumn;
    public $displayType;

    public $column;
    public $type;
    public $fillable;
    protected $data;

    public function __construct($database, $eloquent)
    {
        $this->column = $column;
        $this->type = $type;
        $this->filliable = $filliable;
    }

    protected function setData($data)
    {
        $this->data = $data;
    }

    public function getData($indice = false)
    {
        if (!$indice) {
            return $this->data;
        }

        return $this->data[$indice];
    }

    public function getColumnName()
    {
        return $this->column;
    }

    /**
     * number
     * text
     * text_area
     * rich_text_box
     * 
     * select_dropdown
     * 
     * timestamp
     */
    public function getColumnType()
    {
        $type = $this->getData('type');
        return $type;
    }
    public function getColumnDisplayType()
    {
        $type = $this->getColumnType();

        if ($this->isBelongTo()) {
            $this->displayType = 'relationship';
        }else if ($type == 'int' || $type == 'integer') {
            $this->displayType = 'number';
        }
        return $this->displayType;
    }



    /**
     * 
     */
    public function getName()
    {
        if (!$this->displayName) {
                
            $explode = explode('_', $this->getColumnName());
            $name = '';
            foreach ($explode as $value) {
                if (!empty($name)) {
                    $name .= ' ';
                }
                $name .= ucfirst($value);
            }
            $this->displayName = $name;
        }


        return $this->displayName;
    }

    public function displayFromModel(Model $resultModel)
    {
        $column = $this->getColumnName();

        $result = $resultModel->$column;

        if (is_array($result)) {
            return implode(' - ', $result);
        }

        return $result;
    }

    /**
     * 
     */
    public function isRequired()
    {
        if ($this->getData('notnull') && is_null($this->getData('default'))) {
            return true;
        }
        
        return false;
    }
    /**
     * 
     */
    public function isBrowse()
    {
        if ($this->getColumnType() == 'timestamp') {
            return false;
        }
        return true;
    }
    /**
     * 
     */
    public function isRead()
    {
        if ($this->getColumnType() == 'timestamp') {
            return false;
        }
        return true;
    }
    /**
     * 
     */
    public function isEdit()
    {
        if ($this->getColumnType() == 'timestamp') {
            return false;
        }
        return true;
    }
    /**
     * 
     */
    public function isAdd()
    {
        if ($this->getColumnType() == 'timestamp') {
            return false;
        }
        return true;
    }
    /**
     * 
     */
    public function isDelete()
    {
        if ($this->getColumnType() == 'timestamp') {
            return false;
        }
        return true;
    }



    // 'details'      => [
    //     'slugify' => [
    //         'origin' => 'title',
    //     ],
    //     'validation' => [
    //         'rule'  => 'unique:pages,slug',
    //     ],
    // ],
    // [
    //     'default' => '',
    //     'null'    => '',
    //     'options' => [
    //         '' => '-- None --',
    //     ],
    //     'relationship' => [
    //         'key'   => 'id',
    //         'label' => 'name',
    //     ],
    // ]

    // Image
    // 
    // 'details'      => [
    //     'resize' => [
    //         'width'  => '1000',
    //         'height' => 'null',
    //     ],
    //     'quality'    => '70%',isBelongTo
    //                 'width'  => '300',
    //                 'height' => '250',
    //             ],
    //         ],
    //     ],
    // ],
    public function getDetails()
    {
        $haveDetails = false;
        $array = [];
        if ($relation = $this->isBelongTo()) {
            $haveDetails = true;
            $array['key'] = $relation['key'];
            $array['key'] = $relation['key'];
        }

        if (!$haveDetails) {
            return null;
        }

        return $array;
    }





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
    public static function returnFromArray($data, EloquentService $eloquentService)
    {
        $instanceClass = new static($data['name'], new Varchar, true);
        $instanceClass->setData($data);
        $instanceClass->readEloquentService($eloquentService);

        return $instanceClass;
    }


    /**
     * 
                'details'      => [
                    'model'       => 'Facilitador\\Models\\Role',
                    'table'       => 'roles',
                    'type'        => 'belongsTo',
                    'column'      => 'role_id',
                    'key'         => 'id',
                    'label'       => 'display_name',
                    'pivot_table' => 'roles',
                    'pivot'       => 0,
                ],

     * User hasMany Phones (One to Many)
     * Phone belongsTo User (Many to One) (Inverso do de cima)
     * 
     * belongsToMany (Many to Many) (Inverso é igual)
     * 
     * morphMany
     * morphTo
     * 
     * morphedByMany (O modelo possui a tabela taggables)
     * morphToMany   (nao possui a tabela taggables)
     */
    protected function readEloquentService(EloquentService $eloquentService)
    {
        $relations = $eloquentService->getRelations();
        if (!empty($relations)) {
            foreach ($relations as $relation) {

            }
        }
    }

    protected function isBelongTo()
    {
        $keys = $database->getListTables();
        if (isset($keys[$this->getColumnName()])) {
            return $keys[$this->getColumnName()];
        }

        return false;
    }

    protected function getListTables()
    {
        $keys = [];
        $listTables = \Support\Components\Database\Schema\SchemaManager::listTables();
        foreach ($listTables as $listTable){
            if (!empty($indexes = $listTable->exportIndexesToArray())) {
                foreach ($indexes as $index) {
                    if ($index['type'] == 'PRIMARY') {
                        $keys[$listTable->getName().'_'.$index['columns'][0]] = [
                            'name' => $listTable->getName(),
                            'key' => $index['columns'][0],
                            'label' => 'name'
                        ];
                    }
                }
            }
        }

        return $keys;
    }
}
