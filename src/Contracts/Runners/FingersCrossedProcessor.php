<?php

namespace Support\Contracts\Runners;

use League\Pipeline\FingersCrossedProcessor as FingersCrossedProcessorBase;

class FingersCrossedProcessor extends FingersCrossedProcessorBase
{
    use HasErrors, ArrayableTrait, OutputableTrait;
    // public function process($payload, callable ...$stages)
    // {
    //     foreach ($stages as $stage) {
    //         $payload = $stage($payload);
    //     }

    //     return $payload;
    // }
}