<?php

declare(strict_types=1);


namespace Support\Components\Database\Mount;

use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\TableDiff;
use Support\Components\Database\Schema\SchemaManager;
use Support\Components\Database\Schema\Table;
use Support\Components\Database\Types\Type;
use Support\Traits\Debugger\DevDebug;
use Support\Traits\Debugger\HasErrors;
use Illuminate\Database\Eloquent\Relations\Relation;
use ReflectionClass;
use ReflectionMethod;
use Illuminate\Support\Collection;
use SierraTecnologia\Crypto\Services\Crypto;
use Illuminate\Http\Request;
use Support\Elements\Entities\Relationships;
use App;
use Log;
use Artisan;
use Support\Elements\Entities\DataTypes\Varchar;
use Support\Elements\Entities\EloquentColumn;
use Support\Components\Coders\Parser\ParseModelClass;
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
        $eloquentEntity->setIndexes($indexes);

        $eloquentEntity->setData($tableClassArray);
        $eloquentEntity->setDataForColumns($this->renderDatabaseData["Leitoras"]["displayTables"][$tableName]['columns']);

        $eloquentEntity->setGroup($tableClassArray['group_package']);
        
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


}
