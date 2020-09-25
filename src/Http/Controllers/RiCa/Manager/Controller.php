<?php

namespace Support\Http\Controllers\RiCa\Manager;

use Facilitador\Services\FacilitadorService;
use Support\Http\Controllers\RiCa\Controller as BaseController;
use Support\Services\RepositoryService;

class Controller extends BaseController
{
    
    /**
     * The user repository instance.
     */
    protected $repositoryService;

    /**
     * Create a new controller instance.
     *
     * @param  UserRepository $repositoryService
     * @return void
     */
    public function __construct(FacilitadorService $facilitadorService, RepositoryService $repositoryService)
    {
        $this->repositoryService = $repositoryService;
        parent::__construct($facilitadorService);
    }
}
