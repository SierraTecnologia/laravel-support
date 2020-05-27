<?php

namespace Support\Generators;

use Support\Services\RepositoryService;

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
