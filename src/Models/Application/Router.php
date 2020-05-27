<?php

namespace Support\Models\Application;

use App;
use Support\Patterns\Parser\ComposerParser;
use Illuminate\Support\Facades\Route;
// use Illuminate\Routing\Route;

/**
 * Adds some shared functionality to taks as well as informs the Decoy
 * admin interface.  Also functions as a sort of model.
 */
class Router
{

    /**
     * Admins should not be localized
     *
     * @var boolean
     */
    public static $localizable = false;
    
    public static function all()
    {
        // dd(Route::getRoutes());
        return Route::getRoutes()->get();
    }


}
