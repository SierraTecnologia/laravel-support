<?php

if (!function_exists('config')) {
    function config($address, $defaultValue) {
        return $defaultValue;
    }

    // CHeck if Database.sqlite file exist
    $databaseFileSqlite = __DIR__."/../../database.sqlite";
    if (!file_exists($databaseFileSqlite)) {
        $fh = fopen($databaseFileSqlite, 'w') or die("Can't create file");
    }

    // Configure Database with Laravel Illuminate
    $capsule = new \Illuminate\Database\Capsule\Manager;
    $capsule->addConnection([
    
       "driver" => "sqlite",
    
       "host" => "127.0.0.1",
    
       "database" => $databaseFileSqlite,
    
    //    "username" => "root",
    
    //    "password" => ""
    
    ]);
    
    //Make this Capsule instance available globally.
    $capsule->setAsGlobal();
    
    // Setup the Eloquent ORM.
    $capsule->bootEloquent();
}