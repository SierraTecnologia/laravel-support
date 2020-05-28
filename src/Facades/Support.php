<?php

namespace Support\Facades;

use Illuminate\Support\Facades\Facade;

class Facilitador extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'voyager';
    }
}
