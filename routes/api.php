<?php

use Illuminate\Http\Request;

Route::get('download', ExcelController::class . '@download');

$routePrefix = \Illuminate\Support\Facades\Config::get('siravel.backend-route-prefix', 'siravel');

