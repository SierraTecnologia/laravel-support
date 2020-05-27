<?php

use Illuminate\Support\Str;
use Facilitador\Events\Routing;
use Facilitador\Events\RoutingAdmin;
use Facilitador\Events\RoutingAdminAfter;
use Facilitador\Events\RoutingAfter;
use Facilitador\Facades\Facilitador;

// Route::group(['prefix' => 'facilitador'], function () {
//     Facilitador::routes();
// });

/*
|--------------------------------------------------------------------------
| Facilitador Routes
|--------------------------------------------------------------------------
|
| This file is where you may override any of the routes that are included
| with Facilitador.
|
*/
            

Route::group(
    ['as' => 'facilitador.'], function () {

        Route::namespace('User')->group(
            function () {
                Route::group(
                    ['middleware' => 'admin.user'], function () {
                        event(new RoutingAdmin());

                        // Main Admin and Logout Route
                        Route::get('/', ['uses' => 'FacilitadorController@index',   'as' => 'dashboard']);
                        Route::post('logout', ['uses' => 'FacilitadorController@logout',  'as' => 'logout']);
                        Route::post('upload', ['uses' => 'FacilitadorController@upload',  'as' => 'upload']);

                        Route::get('profile', ['uses' => 'FacilitadorUserController@profile', 'as' => 'profile']);

                        event(new RoutingAdminAfter());
                    }
                );
            }
        );
    }
);
