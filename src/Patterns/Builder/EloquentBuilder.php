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
use Support\Patterns\Parser\ParseModelClass;
use Symfony\Component\Inflector\Inflector;

use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\DBALException;

use Support\Patterns\Entity\EloquentEntity;
use Support\Exceptions\Coder\EloquentTableNotExistException;
use Support\Contracts\Manager\BuilderAbstract;

class EloquentBuilder extends BuilderAbstract
{
    public static $entityClasser = EloquentEntity::class;


    public function builder()
    {
        $parseModelClass = $this->parentEntity->system->models[$this->entity->code];

        $databaseTableObject = false;
        if ($foundTableRender = \Support\Utils\Searchers\ArraySearcher::arraySearchByAttribute(
            $parseModelClass->getTableName(),
            $this->parentEntity->system->tables,
            'name'
        )
        ) {
            $databaseTableObject = $this->parentEntity->system->tables[$foundTableRender[0]];
        }
        if (!$databaseTableObject) {
            throw new EloquentTableNotExistException($this->entity->code, $parseModelClass->getTableName());
        }

        $databaseTableArray = $databaseTableObject->toArray();
        $parseModelClassArray = $parseModelClass->toArray();

        $this->entity->setTablename($parseModelClass->getTableName());
        $this->entity->setName($parseModelClassArray['name']);
        $this->entity->setIcon(\Support\Template\Layout\Icons::getForNameAndCache($parseModelClassArray['name'], false));
        $this->entity->setPrimaryKey($parseModelClass->getPrimaryKey());
        $this->entity->setIndexes($databaseTableArray['indexes']);

        $this->entity->setData($parseModelClassArray);
        $this->entity->setDataForColumns($databaseTableArray['columns']);

        $this->entity->setGroupPackage($parseModelClassArray['groupPackage']);
        $this->entity->setGroupType($parseModelClassArray['groupType']);
        $this->entity->setHistoryType($parseModelClassArray['historyType']);
        $this->entity->setRegisterType($parseModelClassArray['registerType']);


        $columnEntity = \Support\Patterns\Builder\EloquentColumnBuilder::make(
            $this->parentEntity,
            $this->output
        );
        foreach ($databaseTableArray['columns'] as $column) {
            $column['table'] = $parseModelClass->getTableName();
            $this->entity->addColumn($columnEntity($column));
        }
    
    }

}
