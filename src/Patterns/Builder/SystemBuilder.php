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

use Support\Elements\Entities\EloquentEntity;
use Support\Exceptions\Coder\EloquentTableNotExistException;



use Support\Patterns\Parser\ParseClass;
use Support\Utils\Modificators\ArrayModificator;
use Support\Utils\Inclusores\ArrayInclusor;
use Support\Utils\Modificators\StringModificator;
use Support\Utils\Extratores\ClasserExtractor;
use Support\Contracts\Manager\BuilderAbstract;
use Support\Patterns\Entity\SystemEntity;

class SystemBuilder extends BuilderAbstract
{
    public static $entityClasser = SystemEntity::class;

    public $renderDatabase;
    public $renderCoder;

    public function prepare()
    {
        $this->renderDatabase = \Support\Patterns\Render\DatabaseRender::make('', $this->output)();
        $this->renderCoder = \Support\Patterns\Render\CodeRender::make('', $this->output)();
    }
    

    public function builder()
    {
        $this->entity->tables = $this->renderDatabase;
        $results = $this->renderCoder;

        // dd($results);


        $results = (new Collection($results))->reject(
            function ($result) {
                if (!$result) {
                    return true;
                }
                if ($result->hasError()) {
                    return true;
                }
                return false;
            }
        );
        $results->map(
            function ($result) {
                $this->builderClasser($result);
            }
        );

        $results = $results->reject(
            function ($result) {
                if (!$result->typeIs('model')) {
                    return true;
                }
                if (!$result->getTableName()) {
                    return true;
                }
                return false;
            }
        );
        $results->map(
            function ($result) {
                $this->builderEloquent($result);
            }
        );
    }

    // protected function renderData()
    // {
    //     $this->entity = new SystemEntity();
    // }
    protected function builderClasser($modelParser)
    {
        $this->registerMapperClassParents(
            $modelParser->getClassName(),
            $modelParser->getParentClassName()
        );
    }

    protected function builderEloquent($modelParser)
    {
        $this->loadMapperTableToClasses(
            $modelParser->getTableName(),
            $modelParser->getClassName()
        );
        $this->entity->models[$modelParser->getClassName()] = $modelParser;
    }


    private function registerMapperClassParents($className, $classParent)
    {
        if (is_null($className) || empty($className) || is_null($classParent) || empty($classParent) || isset($this->mapperParentClasses[$className])) {
            return false;
        }

        // Ignora Oq nao serve
        if (in_array(
            ClasserExtractor::getClassName($classParent),
            ParseClass::$typesIgnoreName['model']
        )
        ) {
            return false;
        }

        $this->entity->mapperParentClasses[$className] = $classParent;
    }
    private function loadMapperTableToClasses(string $tableName, string $tableClass)
    {
        // Guarda Classe por Table
        if (isset($this->entity->mapperTableToClasses[$tableName])) {
            $this->entity->mapperTableToClasses = ArrayInclusor::setAndPreservingOldDataConvertingToArray(
                $this->entity->mapperTableToClasses,
                $tableName,
                $tableClass
            );

            // @todo Ignorar classes que uma extend a outra
            $this->entity->errors[] = 'Duas classes para a mesma tabela: '.$tableName;
            return ;
        }
        $this->entity->mapperTableToClasses[$tableName] = $tableClass;
    }


}