<?php

declare(strict_types=1);


namespace Support\Mount;

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
use Support\Discovers\Eloquent\EloquentColumn;
use Support\Parser\ParseModelClass;
use Symfony\Component\Inflector\Inflector;

use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\DBALException;

class EloquentMount
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

    protected function render()
    {
        $selfInstance = $this;
        // Cache In Minutes
        $value = Cache::remember('sitec_support_', 30, function () use ($selfInstance) {

            $renderDatabase = (new \Support\Render\Database($this->eloquentClasses));


            $this->eloquentClasses = $renderDatabase->getEloquentClasses->map(function($file, $class) {
                return new \Support\Entitys\EloquentEntity($class);
            })->values()->all();

            return $selfInstance->toArray();
        });
        $this->setArray($value);
        
        // $databaseEntity = new DatabaseEntity();
        
        // $databaseEntity = new DatabaseEntity();
        // $databaseEntity

    }



}
