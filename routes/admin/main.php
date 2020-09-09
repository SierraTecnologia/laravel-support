<?php

Route::get('/', 'AdminController@index')->name('dashboard');
Route::get('/search', 'AdminController@search')->name('globalsearch');
