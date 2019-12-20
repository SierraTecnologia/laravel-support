<?php 

use Illuminate\Database\Capsule\Manager as Capsule;
use Support\Helps\DebugHelper;

class Schema
{
    public static function hasTable($name)
    {
        return Capsule::schema()->hasTable($name);
    }

    public static function create($name, $function)
    {
        DebugHelper::info("[Migrate] Criando tabela: ".$name);
        
        if (Capsule::schema()->hasTable($name)) {
            return true;
        }
        return Capsule::schema()->create($name, $function);
    }
}