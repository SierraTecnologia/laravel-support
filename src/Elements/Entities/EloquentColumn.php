<?php

namespace Support\Elements\Entities;

use Support\Elements\Entities\Relationship;
use Illuminate\Support\Collection;
use Facilitador\Services\ModelService;
use Facilitador\Services\RepositoryService;
use Support\Elements\Entities\DataType;
use Illuminate\Database\Eloquent\Model;

use Support\Elements\Entities\DataTypes\Varchar;
use Symfony\Component\Inflector\Inflector;
use Support\Services\EloquentService;

class EloquentColumn
{

    public $name;
    public $columnName;
    public $columnType;
    public $displayType;

    public $displayName = false;
    public $displayColumn;
    public $fillable;
    protected $data;
    protected $details;

    public function __construct()
    {
        
    }
    
    /**
     * Nome para Exibição
     */
    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        return $this->name = $name;
    }
    
    /**
     * Nome da Coluna no Banco
     */
    public function getColumnName()
    {
        return $this->columnName;
    }

    public function setColumnName($columnName)
    {
        return $this->columnName = $columnName;
    }

    /**
     * Tipo de Coluna no banco
     */
    public function getColumnType()
    {
        return $this->columnType;
    }

    public function setColumnType($columnType)
    {
        return $this->columnType = $columnType;
    }

    /**
     * Tipo de Coluna na aplicacao
     */
    public function getDisplayType()
    {
        return $this->details;
    }

    public function setDisplayType($displayType)
    {
        return $this->displayType = $displayType;
    }

    /**
     * Detalhes
     */
    public function getDetails()
    {
        return $this->details;
    }

    public function setDetails($details)
    {
        return $this->details = $details;
    }
    

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
