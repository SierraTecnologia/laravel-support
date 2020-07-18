<?php

declare(strict_types=1);

namespace Support\Traits\Debugger;

trait DevDebug
{

    /**
     * Helpers for Development @todo Tirar daqui
     */ 
    // @todo Tirar essa gambiarra
    public $isDebugging = false;
    public $modelsForDebug = [
        
    ];


    /**
     * Helpers for Development
     */ 
    public function sendToDebug($data)
    {
        if (!$this->isDebugging) {
            return ;
        }

        echo 'DevDebug ... ';

        dd('DebugData', $data);
    }
}
