<?php

declare(strict_types=1);


namespace Support\Patterns\Builder;

use Support\Utils\Modificators\ArrayModificator;
use Support\Utils\Modificators\StringModificator;
use Support\Traits\Coder\GetSetTrait;
use Support\Components\Database\Schema\Table;
use Log;
use Support\Contracts\Output\OutputableTrait;
use Support\Contracts\Manager\BuilderAbstract;
use Support\Patterns\Entity\ApplicationEntity;
use Illuminate\Database\Eloquent\Collection;

class ApplicationBuilder extends BuilderAbstract
{
    public static $entityClasser = ApplicationEntity::class;



    public function prepare()
    {
        $this->systemEntity = \Support\Patterns\Builder\SystemBuilder::make('', $this->output)();
    }

    public function builder()
    {

        $this->entity->system = $this->systemEntity;
        $results = $this->systemEntity->models;
        (new Collection($results))->map(
            function ($result) {
                $this->builderChildren($result);
            }
        );

        
    }

    public function builderChildren($result)
    {
        $this->entity->models[$result->getClassName()] = \Support\Patterns\Builder\EloquentBuilder::make(
            $this->entity,
            $this->output
        )($result->getClassName());
    }

}
