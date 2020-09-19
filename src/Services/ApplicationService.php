<?php
/**
 * @todo Deveria ir pro faciltador {NOVA REORGANIZACAO}
 */

namespace Support\Services;

use Illuminate\Support\Collection;
use Support\Components\Database\Mount\DatabaseMount;
use Support\Entities\EloquentEntity;
use Pedreiro\Exceptions\Coder\EloquentHasErrorException;
use Pedreiro\Exceptions\Coder\EloquentNotExistException;
use Support\Patterns\Builder\ApplicationBuilder;
use Support\Patterns\Entity\ApplicationEntity;
use Support\Patterns\Parser\ComposerParser;

class ApplicationService
{
    protected $entity = false;

    public function __construct()
    {
    }

    public function getEntity()
    {
        if (!$this->entity && !$this->entity = ApplicationEntity::recover()) {
            $this->forceBuilder();
        }
        return $this->entity;
    }

    public function forceBuilder()
    {
        return $this->entity = ApplicationBuilder::make('')();
    }
}
