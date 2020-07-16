<?php

// Route::group(['middleware' => 'admin.user'], function () {
    Route::name('facilitador.')->group(
        function () {
            Route::prefix('manager')->group(
                function () {
                    Route::namespace('Manager')->group(
                        function () {

                            /**
                             * By Model
                             */
                            Route::prefix('{modelClass}')->group(
                                function () {

                                    /**
                                     * Repository Controller
                                     */

                                    Route::get('/order', 'RepositoryController@order')->name('order');
                                    Route::post('/action', 'RepositoryController@action')->name('action');
                                    Route::post('/order', 'RepositoryController@update_order')->name('order');
                                    Route::get('/relation', 'RepositoryController@relation')->name('relation');

                                    Route::get('/', 'RepositoryController@index')->name('index');
                                    Route::get('/create', 'RepositoryController@create')->name('create');
                                    Route::post('/store', 'RepositoryController@store')->name('store');

                                    /**
                                     * Register Controller
                                     */
                                    // Route::prefix('r')->group(
                                    //     function () {
                                            Route::prefix('{identify}')->group(
                                                function () {
                                                    Route::get('/', 'RegisterController@index')->name('show');
                                                    Route::get('/show', 'RegisterController@index')->name('show');
                                                    Route::get('/edit', 'RegisterController@edit')->name('edit');
                                                    Route::put('/', 'RegisterController@update')->name('update');
                                                    Route::delete('/', 'RegisterController@destroy')->name('destroy');
                                                    Route::post('/remove', 'RegisterController@remove_media')->name('media.remove');
                                                    Route::get('/restore', 'RegisterController@restore')->name('restore');
                                                }
                                            );
                                    //     }
                                    // );


                                    // /**
                                    //  * Repository Controller
                                    //  */
                                    // Route::get('/', 'OldRepositoryController@index')->name('index');
                                    // Route::get('/create', 'OldRepositoryController@create')->name('create');
                                    // Route::post('/', 'OldRepositoryController@store')->name('store');
                                    // Route::get('/search', 'OldRepositoryController@search')->name('search');

                                    // /**
                                    //  * Register Controller
                                    //  */
                                    // Route::prefix('{identify}')->group(function () {
                                    //     Route::get('/', 'OldRegisterController@index')->name('show');
                                    //     Route::get('/edit', 'OldRegisterController@edit')->name('edit');
                                    //     Route::put('/', 'OldRegisterController@update')->name('update');
                                    //     Route::delete('/', 'OldRegisterController@destroy')->name('destroy');
                                    // });
                                }
                            );
                        }
                    );
                }
            );
        }
    );
    // });