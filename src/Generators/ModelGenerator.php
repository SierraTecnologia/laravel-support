<?php

namespace Support\Generators;

use Support\Services\ModelService;

/**
 * Generate the CRUD.
 */
class ModelGenerator
{
    public function __construct(ModelService $service)
    {
        $this->service = $service;
    }
}
