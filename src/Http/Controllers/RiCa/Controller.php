<?php

namespace Support\Http\Controllers\RiCa;

use Facilitador\Services\FacilitadorService;
use Pedreiro\Http\Controllers\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * The user repository instance.
     */
    protected $facilitadorService;
    /**
     * Create a new controller instance.
     *
     * @param  FacilitadorService $facilitadorService
     * @return void
     */
    public function __construct(FacilitadorService $facilitadorService)
    {
        $this->facilitadorService = $facilitadorService;
    }
}
