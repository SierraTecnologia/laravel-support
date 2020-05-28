<?php

use Illuminate\Support\Str;
use Facilitador\Events\Routing;
use Facilitador\Events\RoutingAdmin;
use Facilitador\Events\RoutingAdminAfter;
use Facilitador\Events\RoutingAfter;
use Facilitador\Facades\Facilitador;

// Public routes
Route::group(
    [
    'prefix' => \Illuminate\Support\Facades\Config::get('sitec.core.dir', 'admin'),
    'middleware' => 'web',
    ], function () {
        $loadingRoutes = [
        'manager',
        'facilitador'
        ];
        event(new Routing());
        foreach ($loadingRoutes as $loadingRoute) {
            include dirname(__FILE__) . DIRECTORY_SEPARATOR . "web". DIRECTORY_SEPARATOR . $loadingRoute.".php";
        }
        event(new RoutingAfter());
    }
);    