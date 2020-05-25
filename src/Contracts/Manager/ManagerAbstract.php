<?php

namespace Support\Contracts\Manager;

use Support\Contracts\Support\Arrayable;
use Support\Contracts\Support\ArrayableTrait;
use Support\Traits\Debugger\HasErrors;
use Support\Traits\Coder\GetSetTrait;
use Support\Contracts\Output\OutputableTrait;
use Illuminate\Database\Eloquent\Collection;

abstract class ManagerAbstract  implements Arrayable
{
    use HasErrors, ArrayableTrait, OutputableTrait;

    protected $data;
    
    public function __construct($output = false)
    {
        $this->output = $output;
        $this->run();
    }

    public function __invoke()
    {
        return $this->data;
        // return $this->getChildrens();
    }

    public function getData()
    {
        return $this->data;
    }
}