<?php

namespace Support\Elements\Entities;

use Illuminate\Support\Collection;
use Facilitador\Services\ModelService;
use Facilitador\Services\RepositoryService;
use Support\Elements\Entities\DataType;
use Illuminate\Database\Eloquent\Model;

use Support\Elements\Entities\DataTypes\Varchar;
use Support\Services\EloquentService;
use Support\Traits\Coder\GetSetTrait;

class EloquentColumn
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



    public $displayName = false;
    public $displayColumn;
    public $fillable;
    protected $data;



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
}
