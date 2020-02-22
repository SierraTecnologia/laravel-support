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

class Eloquent
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
    protected $tableName;
    protected $colunasDaTabela;
    protected $columns;
    protected $indexes;
    protected $primaryKey;
    protected $attributes;

    protected $relations = false;

    /**
     * NOt Cached
     */
    protected $schemaManagerTable;
    protected $hardParserModelClass;

    /**
     * Construct
     */
    public function __construct($modelClass = false, $render = false)
    {
        if (in_array($modelClass, $this->modelsForDebug)) {
            $this->debug = true;
        }

        if ($this->modelClass = $modelClass && $render) {
            $this->render();
        }

        // dd($this->toArray());
    }



}
