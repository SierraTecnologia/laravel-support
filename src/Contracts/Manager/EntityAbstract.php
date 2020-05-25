<?php
namespace Support\Contracts\Manager;

use Support\Contracts\Support\Arrayable;
use Support\Contracts\Support\ArrayableTrait;
use Support\Traits\Debugger\HasErrors;
use Support\Traits\Coder\GetSetTrait;
use Support\Contracts\Output\OutputableTrait;
use Illuminate\Database\Eloquent\Collection;

abstract class EntityAbstract
{
    protected $code;
    
    public function __construct($code = '')
    {
        $this->code = $code;
    }
    
}