<?php

declare(strict_types=1);

namespace Support\ClassesHelpers\Development;

trait DevDebug
{

    /**
     * Helpers for Development @todo Tirar daqui
     */ 
    // @todo Tirar essa gambiarra
    public $debug = false;
    public $modelsForDebug = [
        // \Population\Models\Identity\Digital\Account::class,
        // \Population\Models\Identity\Digital\Email::class,
    ];


    /**
     * Helpers for Development
     */ 
    protected function sendToDebug($data)
    {
        if (!$this->debug) {
            return ;
        }

        echo 'DevDebug ... ';

        dd($data);
    }
}
