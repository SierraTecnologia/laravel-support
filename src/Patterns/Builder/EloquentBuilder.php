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
use Support\Contracts\Manager\BuilderAbstract;

class EloquentBuilder extends BuilderAbstract
{
    public static $entityClasser = EloquentEntity::class;


    public function builder(): bool
    {
        $parseModelClass = $this->parentEntity->system->models[$this->entity->code];

        $databaseTableObject = $this->parentEntity->system->returnTableForName($parseModelClass->getTableName());

        $databaseTableArray = $databaseTableObject->toArray();
        $parseModelClassArray = $parseModelClass->toArray();

        $this->entity->setTablename($parseModelClass->getTableName());
        $this->entity->setName($parseModelClassArray['name']);
        $this->entity->setDisplayName($this->getDisplayName($databaseTableObject, $databaseTableArray['columns'], $parseModelClass->getPrimaryKey()));
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
            if ($entityColumn = $columnEntity($column)) {
                $this->entity->addColumn($entityColumn);
            }
        }

        
        return true;
    
    }



    private function getDisplayName(Table $listTable, array $columns, string $primaryKey): string
    {

        // Qual coluna ira mostrar em uma Relacao ?
        if ($listTable->hasColumn('name')) {
            return 'name';
        } 
        if ($listTable->hasColumn('displayName')) {
            return 'displayName';
        }

        foreach ($columns as $column) {
            if ($column['type']['name'] == 'varchar') {
                return $column['name'];
            }
        }
        return $primaryKey;
    }
}
