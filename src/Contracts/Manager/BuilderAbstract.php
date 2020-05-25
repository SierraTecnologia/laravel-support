<?php
namespace Support\Contracts\Manager;

use Support\Contracts\Support\Arrayable;
use Support\Contracts\Support\ArrayableTrait;
use Support\Traits\Debugger\HasErrors;
use Support\Traits\Coder\GetSetTrait;
use Support\Contracts\Output\OutputableTrait;
use Illuminate\Database\Eloquent\Collection;

abstract class BuilderAbstract extends ManagerAbstract
{
    protected $entity;
    
    /**
     * Atributos
     */
    use GetSetTrait;

    public static function make($output = false)
    {
        return new static($output);
    }


    public function run()
    {
        $this->info('Rodando Builder: '.static::class);
        
        $this->builder();
        return true;
    }
}