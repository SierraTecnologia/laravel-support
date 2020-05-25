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

use Support\Elements\Entities\EloquentEntity;
use Support\Exceptions\Coder\EloquentTableNotExistException;
use Support\Contracts\Manager\BuilderAbstract;
use Support\Patterns\Entity\CodeEntity;

class CodeBuilder extends BuilderAbstract
{
    

    public function builder()
    {
        $results = \Support\Patterns\Render\CodeRender::make('', $this->output)();

        // dd($results);

        $this->entity = new CodeEntity();

        $results->reject(
            function ($result) {
                if (!$result) {
                    return true;
                }
                if (!$result->getTableName()) {
                    return true;
                }
                if ($result->hasError()) {
                    return true;
                }
                if (!$result->typeIs('model')) {
                    return true;
                }
                return false;
            }
        )->map(
            function ($result) {
                $this->builderEloquent($result);
            }
        );
        // // $results = $render->getData();
        // foreach ($results as $indice=>$result){
        //     }
        //     $this->builderEloquent($result);
        // }

        dd($this->entity);
    }

    // protected function renderData()
    // {
    //     $this->entity = new CodeEntity();
    // }

    protected function builderEloquent($modelParser)
    {
        $this->registerMapperClassParents(
            $modelParser->getClassName(),
            $modelParser->getParentClassName()
        );
    }

    public function registerMapperClassParents($className, $classParent)
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

}
