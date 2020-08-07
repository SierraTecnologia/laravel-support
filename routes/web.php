<?php

use Illuminate\Support\Str;
use Support\Events\Routing;
use Support\Events\RoutingAdmin;
use Support\Events\RoutingAdminAfter;
use Support\Events\RoutingAfter;
use Support\Facades\Support;

// Nao usa mais , ta carregando o rica pelo router
// // Public routes
// Route::group(
//     [
//     'prefix' => \Illuminate\Support\Facades\Config::get('generators.core.dir', 'siravel'),
//     'middleware' => 'web',
//     ], function () {
//         $loadingRoutes = [
//             'manager',
//             'voyager'
//         ];
//         event(new Routing());
//         foreach ($loadingRoutes as $loadingRoute) {
//             include dirname(__FILE__) . DIRECTORY_SEPARATOR . "rica". DIRECTORY_SEPARATOR . $loadingRoute.".php";
//         }
//         event(new RoutingAfter());
//     }
// );    