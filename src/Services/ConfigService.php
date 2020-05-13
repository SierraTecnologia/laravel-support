<?php
/**
 * Serviço referente a linha no banco de dados
 */

namespace Support\Services;

/**
 * ConfigService helper to make table and object form mapping easy.
 */
class ConfigService
{


    public function __construct()
    {
        
        
    }
    public function get($config, $default = false)
    {
        return \Illuminate\Support\Facades\Config::get($config, $default);
    }

    public static function getInstance()
    {
        return new self;
    }
}
