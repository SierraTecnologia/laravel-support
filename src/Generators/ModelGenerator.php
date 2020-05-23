<?php

namespace Facilitador\Generators;

use Facilitador\Services\ModelService;

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
