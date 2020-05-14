<?php 

namespace Support\Helps;

use Illuminate\Database\Capsule\Manager as Capsule;

class ConfigHelper
{
    public static function ignoreFolders()
    {
        return [
            'vendor'
        ];
    }
}