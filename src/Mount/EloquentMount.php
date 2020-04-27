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

use Support\Elements\Entities\EloquentEntity;

class EloquentMount
{
    use DevDebug;
    use HasErrors;
    /**
     * Identify
     */
    protected $className;
    protected $renderDatabaseData;

    /**
     * Construct
     */
    public function __construct($className, $renderDatabase)
    {
        $this->className = $className;
        $this->renderDatabaseData = $renderDatabase;
    }

    public function getEntity()
    {

        $tableName = $this->renderDatabaseData["Leitoras"]["displayClasses"][$this->className]["tableName"];
        $name = $this->renderDatabaseData["Leitoras"]["displayClasses"][$this->className]["name"];
        $icon = $this->renderDatabaseData["Leitoras"]["displayClasses"][$this->className]["icon"];
        $tableClassArray = $this->renderDatabaseData["Leitoras"]["displayClasses"][$this->className]["tableData"];
        $primaryKey = $tableClassArray["getKeyName"];
        

        if (!isset($this->renderDatabaseData["Leitoras"]["displayTables"][$tableName])) {
            // @todo criar erro
            return false;
        }

        $indexes = $this->renderDatabaseData["Leitoras"]["displayTables"][$tableName][
            'indexes'
        ];
// dd(
//         $this->renderDatabaseData["Leitoras"]["displayClasses"][$this->className],
//         $this->renderDatabaseData["Leitoras"]["displayTables"][$tableName]
// );
        $eloquentEntity = new EloquentEntity($this->className);
        $eloquentEntity->setTablename($tableName);
        $eloquentEntity->setName($name);
        $eloquentEntity->setIcon($icon);
        $eloquentEntity->setPrimaryKey($primaryKey);
        $eloquentEntity->setData($tableClassArray);
        $eloquentEntity->setIndexes($indexes);
        
        foreach ($this->renderDatabaseData["Leitoras"]["displayTables"][$tableName]['columns'] as $column) {
            $eloquentEntity->addColumn( (new ColunMount($this->className, $column, $this->renderDatabaseData))->getEntity());
        }

        // Debug
        // if ($tableName=='persons') {
        //     dd(
        //         $this->renderDatabaseData["Leitoras"]["displayTables"][$tableName],
        //         $eloquentEntity
        //     );
        // }

        return $eloquentEntity;
    }

    public function toArray()
    {
        $array = [];

        return $array;
    }

    // protected function render()
    // {
    //     $selfInstance = $this;
    //     // Cache In Minutes
    //     $value = Cache::remember('sitec_support_', 30, function () use ($selfInstance) {

    //         $renderDatabase = (new \Support\Render\Database($this->eloquentClasses));


    //         $this->eloquentClasses = $renderDatabase->getEloquentClasses->map(function($file, $class) {
    //             return new \Support\Elements\Entities\EloquentEntity($class);
    //         })->values()->all();

    //         return $selfInstance->toArray();
    //     });
    //     $this->setArray($value);
        
    //     // $databaseEntity = new DatabaseEntity();
        
    //     // $databaseEntity = new DatabaseEntity();
    //     // $databaseEntity

    // }



}
