<?php

declare(strict_types=1);


namespace Support\Components\Database\Mount;

use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\TableDiff;
use Support\Components\Database\Schema\SchemaManager;
use Support\Components\Database\Schema\Table;
use Support\Components\Database\Types\Type;
use Muleta\Traits\Debugger\DevDebug;
use Muleta\Traits\Debugger\HasErrors;
use Illuminate\Database\Eloquent\Relations\Relation;
use ReflectionClass;
use ReflectionMethod;
use Illuminate\Support\Collection;
use SierraTecnologia\Crypto\Services\Crypto;
use Illuminate\Http\Request;
use App;
use Log;
use Artisan;
use Pedreiro\Elements\DataTypes\Varchar;
use Support\Entities\EloquentColumn;
use Support\Patterns\Parser\ParseModelClass;
use Symfony\Component\Inflector\Inflector;

use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\DBALException;

use Support\Entities\EloquentEntity;
use Pedreiro\Exceptions\Coder\EloquentTableNotExistException;

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

        $eloquentClassArray = $this->renderDatabaseData["Leitoras"]["displayClasses"][$this->className];
        $tableName = $eloquentClassArray["tableName"];

        $databaseTableArray = false;
        if ($foundTableRender = \Muleta\Utils\Searchers\ArraySearcher::arraySearchByAttribute(
            $tableName,
            $this->renderDatabaseData["Leitoras"]["displayTables"],
            'name'
        )
        ) {
            $databaseTableArray = $this->renderDatabaseData["Leitoras"]["displayTables"][$foundTableRender[0]];
        }
        // Procura nas tabelas de relacionamento
        if ($foundTableRender = \Muleta\Utils\Searchers\ArraySearcher::arraySearchByAttribute(
            $tableName,
            $this->renderDatabaseData["AplicationTemp"]["tempAppTablesWithNotPrimaryKey"],
            'name'
        )
        ) {
            $databaseTableArray = $this->renderDatabaseData["AplicationTemp"]["tempAppTablesWithNotPrimaryKey"][$foundTableRender[0]];
        }
        if (!$databaseTableArray) {
            throw new EloquentTableNotExistException($this->className, $tableName);
        }
        $eloquentClassDataArray = $eloquentClassArray["tableData"];

        $name = $eloquentClassArray["name"];
        $icon = $eloquentClassArray["icon"];
        $primaryKey = $eloquentClassDataArray["getKeyName"];

        $indexes = $databaseTableArray[
            'indexes'
        ];
        $eloquentEntity = new EloquentEntity($this->className);
        $eloquentEntity->setTablename($tableName);
        $eloquentEntity->setName($name);
        $eloquentEntity->setIcon($icon);
        $eloquentEntity->setPrimaryKey($primaryKey);
        $eloquentEntity->setIndexes($indexes);

        $eloquentEntity->setData($eloquentClassDataArray);
        $eloquentEntity->setDataForColumns($databaseTableArray['columns']);

        $eloquentEntity->setGroupPackage($eloquentClassDataArray['groupPackage']);
        $eloquentEntity->setGroupType($eloquentClassDataArray['groupType']);
        $eloquentEntity->setHistoryType($eloquentClassDataArray['historyType']);
        $eloquentEntity->setRegisterType($eloquentClassDataArray['registerType']);
        
        foreach ($databaseTableArray['columns'] as $column) {
            if ($columnEntity = (new ColunMount($this->className, $column, $this->renderDatabaseData))->getEntity()) {
                $eloquentEntity->addColumn($columnEntity);
            }
        }

        // Debug
        // if ($tableName=='persons') {
        //     dd(
        //         $databaseTableArray,
        //         $eloquentEntity
        //     );
        // }

        return $eloquentEntity;
    }


}
