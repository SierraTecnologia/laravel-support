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

class ModelagemBuilder extends BuilderAbstract
{

    public function builder()
    {


        $renderCode = \Support\Patterns\Render\CodeRender::make('', $this->output)();
        $renderDatabase = \Support\Patterns\Render\DatabaseRender::make('', $this->output)();


        
    }

}
