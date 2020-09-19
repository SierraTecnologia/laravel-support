<?php

namespace Support\Services;

use Support\Components\Database\Mount\DatabaseMount;
use Support\Entities\EloquentEntity;
use Support\Patterns\Parser\ComposerParser;
use Illuminate\Support\Collection;
use Support\Exceptions\Coder\EloquentNotExistException;
use Support\Exceptions\Coder\EloquentHasErrorException;
use Support\Patterns\Builder\ApplicationBuilder;
use Support\Patterns\Entity\ApplicationEntity;

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
