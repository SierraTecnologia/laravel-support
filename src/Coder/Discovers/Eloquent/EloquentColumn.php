<?php

namespace Support\Coder\Discovers\Eloquent;

use Support\Coder\Discovers\Eloquent\Relationship;
use Illuminate\Support\Collection;
use Facilitador\Services\ModelService;
use Facilitador\Services\RepositoryService;
use Support\Elements\Entities\DataType;
use Illuminate\Database\Eloquent\Model;

class EloquentColumn
{
    public $column;
    public $type;
    public $fillable;

    public function __construct(string $column, DataType $type, bool $filliable = false)
    {
        $this->column = $column;
        $this->type = $type;
        $this->filliable = $filliable;
    }

    public function getColumnName()
    {
        return $this->column;
    }

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
}
