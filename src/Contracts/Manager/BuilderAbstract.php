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

    /**
     * Identify
     */
    protected $parentEntity;


    public static function make($parentEntity, $output = false)
    {
        return new static($parentEntity, $output);
    }
    /**
     * Construct
     */
    public function __construct($parentEntity, $output)
    {
        $this->parentEntity = $parentEntity;
        parent::__construct($output);
    }

    public function __invoke($coder = false)
    {
        if (!$coder) {
            $this->entity = new static::$entityClasser;
        } else {
            $this->entity = new static::$entityClasser($coder);
        }
        $this->prepare();
        if ($this->builder()) {
            return $this->entity;
        }
        return null;
    }


    public function run()
    {
        $this->info('Rodando Builder: '.static::class);
        $this->requeriments();
        return true;
    }

    public function requeriments()
    {
        
    }
    public function prepare()
    {
        
    }
}