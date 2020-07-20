<?php

namespace Support\Patterns\Entity;

use Support\Contracts\Manager\EntityAbstract;

use Illuminate\Support\Collection;
use Support\Services\ModelService;
use Support\Services\RepositoryService;
use Support\Elements\Entities\DataType;
use Illuminate\Database\Eloquent\Model;

use Support\Elements\Entities\DataTypes\Varchar;
use Support\Services\EloquentService;
use Muleta\Traits\Coder\GetSetTrait;


class EloquentColumnEntity extends EntityAbstract
{

    /**
     * Atributos
     */
    use GetSetTrait;

    /**
     * name
     *
     * @var    string
     * @getter true
     * @setter true
     */
    protected $name;


    /**
     * columnName
     *
     * @var    string
     * @getter true
     * @setter true
     */
    protected $columnName;

    /**
     * columnType
     *
     * @var    string
     * @getter true
     * @setter true
     */
    protected $columnType;

    /**
     * displayType
     *
     * @var    string
     * @getter true
     * @setter true
     */
    protected $displayType;

    /**
     * details
     *
     * @var    array
     * @getter true
     * @setter true
     */
    protected $details;

    /**
     * Se é campo de update date
     *
     * @var    bool
     * @getter true
     * @setter true
     */
    public $isUpdatedDate = false;

    /**
     * Se é campo de update date
     *
     * @var    bool
     * @getter true
     * @setter true
     */
    public $isCreatedDate = false;



    public $displayName = false;
    public $displayColumn;
    public $fillable;
    protected $data;

    public static $mapper = [
        'name',
        'columnName',
        'data',
    ];


    /**
     * @todo esse codigo é reptid remover depois
     */
    public function getData($indexe = false)
    {
        if (empty($indexe) || !isset($this->data[$indexe])) {
            return $this->data;
        }
        return $this->data[$indexe];
    }
    public function setData($data)
    {
        return $this->data = $data;
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
        if ($this->isCreatedDate || $this->isUpdatedDate) {
            return false;
        }

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
        if ($this->isUpdatedDate) {
            return false;
        }
        return true;
    }
    /**
     * 
     */
    public function isRead()
    {
        return true;
    }
    /**
     * 
     */
    public function isEdit()
    {
        if ($this->isCreatedDate || $this->isUpdatedDate) {
            return false;
        }
        return true;
    }
    /**
     * 
     */
    public function isAdd()
    {
        if ($this->isCreatedDate || $this->isUpdatedDate) {
            return false;
        }
        return true;
    }
    /**
     * 
     */
    public function isDelete()
    {
        if ($this->isCreatedDate || $this->isUpdatedDate) {
            return false;
        }
        return true;
    }
}
