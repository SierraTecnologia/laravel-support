<?php

declare(strict_types=1);


namespace Support\Patterns\Builder;

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
use App;
use Log;
use Artisan;
use Support\Elements\Entities\DataTypes\Varchar;
use Support\Elements\Entities\EloquentColumn;
use Support\Components\Coders\Parser\ParseModelClass;
use Symfony\Component\Inflector\Inflector;

use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\DBALException;

use Support\Patterns\Entity\CodeEloquentEntity;
use Support\Exceptions\Coder\EloquentTableNotExistException;
use Support\Contracts\Manager\BuilderAbstract;

class CodeEloquentBuilder extends BuilderAbstract
{
    public static $entityClasser = EloquentEntity::class;


    public function builder()
    {

        $eloquentClassArray = $this->parentEntity->models[$this->className];
        $tableName = $eloquentClassArray["tableName"];

        $databaseTableArray = false;
        if ($foundTableRender = \Support\Utils\Searchers\ArraySearcher::arraySearchByAttribute(
            $tableName,
            $this->parentEntity->tables,
            'name'
        )
        ) {
            $databaseTableArray = $this->parentEntity->tables[$foundTableRender[0]];
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
        // dd(
        //         $eloquentClassArray,
        //         $this->renderDatabaseData["Leitoras"]["displayTables"][$tableName]
        // );
        $this->entity->setTablename($tableName);
        $this->entity->setName($name);
        $this->entity->setIcon($icon);
        $this->entity->setPrimaryKey($primaryKey);
        $this->entity->setIndexes($indexes);

        $this->entity->setData($eloquentClassDataArray);
        $this->entity->setDataForColumns($databaseTableArray['columns']);

        $this->entity->setGroupPackage($eloquentClassDataArray['groupPackage']);
        $this->entity->setGroupType($eloquentClassDataArray['groupType']);
        $this->entity->setHistoryType($eloquentClassDataArray['historyType']);
        $this->entity->setRegisterType($eloquentClassDataArray['registerType']);
        
        $columnEntity = \Support\Patterns\Builder\EloquentColumnBuilder::make(
            $this->parentEntity,
            $this->output
        );
        foreach ($databaseTableArray['columns'] as $column) {
            $this->entity->addColumn($columnEntity($column));
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
