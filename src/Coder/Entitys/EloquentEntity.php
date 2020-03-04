<?php

namespace Support\Coder\Entitys;

use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\TableDiff;
use Support\Coder\Discovers\Database\Schema\SchemaManager;
use Support\Coder\Discovers\Database\Schema\Table;
use Support\Coder\Discovers\Database\Types\Type;
use Support\ClassesHelpers\Development\DevDebug;
use Support\ClassesHelpers\Development\HasErrors;
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
     * Cached
     */
    protected $render;

    /**
     * Construct
     */
    public function __construct($modelClass = false, $render = false)
    {
        // dd($this->toArray());
    }
    public function getModelClass()
    {

        return $this->modelClass;
    }

    public function getName()
    {

        return $this->getData('name');
    }

    public function getData($data)
    {
        return $this->render->displayClasses[$this->getModelClass][$data];
    }



}
