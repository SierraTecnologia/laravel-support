<?php
/**
 * Serviço referente a linha no banco de dados
 */

namespace Muleta\Services;

use Log;

/**
 * 
 */
class LoggerService
{

    protected $name;

    protected $logs = [];

    public function __construct($name)
    {
        $this->name = $name;
        // if (!$this->config = $config) {
        //     $this->config = \Illuminate\Support\Facades\Config::get('sitec.sitec.models');
        // }
    }

    public function addLogger($logMessage): void
    {
        $this->logs[] = $logMessage;
    }

    public function __destruct()
    {
        $i = 0;
        foreach ($this->logs as $logger) {
            Log::debug($this->name.' | '.$i.' | '.$logger);
            ++$i;
        }
    }

}
