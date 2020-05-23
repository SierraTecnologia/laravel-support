<?php

namespace Facilitador\Generators;

use Facilitador\Services\RepositoryService;

/**
 * Generate the CRUD.
 */
class RepositoryGenerator
{
    public function __construct(RepositoryService $service)
    {
        $this->service = $service;
    }
}
