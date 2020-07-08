<?php

namespace Support\Contracts\Runners;

use League\Pipeline\StageInterface;

use Support\Contracts\Support\Arrayable;
use Support\Contracts\Support\ArrayableTrait;
use Support\Traits\Debugger\HasErrors;
use Support\Traits\Coder\GetSetTrait;
use Support\Contracts\Output\OutputableTrait;
use Illuminate\Database\Eloquent\Collection;

class Stage// implements StageInterface
{
    use HasErrors, ArrayableTrait, OutputableTrait;

    
    public function attributes()
    {
        return [
            Attributes\Time::class(),
            Attributes\Target::class(),
        ];
    }
}