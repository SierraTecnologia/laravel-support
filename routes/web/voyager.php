<?php

use Illuminate\Support\Str;
use Facilitador\Events\Routing;
use Facilitador\Events\RoutingAdmin;
use Facilitador\Events\RoutingAdminAfter;
use Facilitador\Events\RoutingAfter;
use Facilitador\Facades\Facilitador;

// Route::group(['prefix' => 'facilitador'], function () {
//     Support::routes();
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
            
Route::namespace('Manager')->group(
    function () {

        Route::group(
            ['as' => 'facilitador.'], function () {

                Route::group(
                    ['middleware' => 'admin.user'], function () {
                        event(new RoutingAdmin());

                        // Database Routes
                        Route::resource('database', 'FacilitadorDatabaseController');


                        /**
                         * Fim do Para Corrigir Bugs
                        */

                        event(new RoutingAdminAfter());
                    }
                );

            }
        );
    }
);

            
Route::namespace('Admin')->group(
    function () {

        Route::group(
            ['as' => 'facilitador.'], function () {

                // Route::group(
                //     ['middleware' => 'admin.user'], function () {
                       
                        Route::get('commands', [
                            'as' => 'commands',
                            'uses' => '\Support\Http\Controllers\Admin\Commands@index',
                        ]);

                        Route::post('commands/{command}', [
                            'as' => 'commands@execute',
                            'uses' => '\Support\Http\Controllers\Admin\Commands@execute',
                        ]);

                        Route::get('routers', [
                            'as' => 'routers',
                            'uses' => '\Support\Http\Controllers\Admin\Routers@index',
                        ]);
                    // }
                // );

            }
        );
    }
);
