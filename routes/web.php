<?php

use Illuminate\Support\Str;
use Support\Events\Routing;
use Support\Events\RoutingAdmin;
use Support\Events\RoutingAdminAfter;
use Support\Events\RoutingAfter;
use Support\Facades\Support;

// Public routes
Route::group(
    [
    'prefix' => \Illuminate\Support\Facades\Config::get('generators.core.dir', 'siravel'),
    'middleware' => 'web',
    ], function () {
        $loadingRoutes = [
            'manager',
            'voyager'
        ];
        event(new Routing());
        foreach ($loadingRoutes as $loadingRoute) {
            include dirname(__FILE__) . DIRECTORY_SEPARATOR . "web". DIRECTORY_SEPARATOR . $loadingRoute.".php";
        }
        event(new RoutingAfter());
    }
);    