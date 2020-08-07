<?php

namespace Support\Facades;

use Illuminate\Support\Facades\Facade;

class SupportURL extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'support.url';
    }
}
