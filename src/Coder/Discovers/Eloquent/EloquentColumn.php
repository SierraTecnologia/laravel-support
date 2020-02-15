<?php

namespace Support\Coder\Discovers\Eloquent;

use Support\Coder\Discovers\Eloquent\Relationship;
use Illuminate\Support\Collection;
use Facilitador\Services\ModelService;
use Facilitador\Services\RepositoryService;
use Support\Elements\Entities\DataType;
use Illuminate\Database\Eloquent\Model;

use Support\Elements\Entities\DataTypes\Varchar;

use Support\Services\EloquentService;

class EloquentColumn
{
    public $column;
    public $type;
    public $fillable;
    protected $data;

    public function __construct(string $column, DataType $type, bool $filliable = false)
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
        return $this->getData('type');
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
    //     'quality'    => '70%',
    //     'upsize'     => true,
    //     'thumbnails' => [
    //         [
    //             'name'  => 'medium',
    //             'scale' => '50%',
    //         ],
    //         [
    //             'name'  => 'small',
    //             'scale' => '25%',
    //         ],
    //         [
    //             'name' => 'cropped',
    //             'crop' => [
    //                 'width'  => '300',
    //                 'height' => '250',
    //             ],
    //         ],
    //     ],
    // ],
    public function getDetails()
    {
        return null;
    }



    /**
     * 
     */
    public function getName()
    {
        return ucfirst($this->column);
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
    protected function readEloquentService(EloquentService $eloquentService)
    {
        
    }
}
