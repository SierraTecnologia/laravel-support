<?php
namespace Support\Contracts\Manager;

use Muleta\Traits\Debugger\HasErrors;
use Muleta\Traits\Coder\GetSetTrait;
use Illuminate\Database\Eloquent\Collection;
use Support\Repositories\EntityRepository;

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

    /**
     * Construct
     */
    public function __construct($parentEntity)
    {
        $this->parentEntity = $parentEntity;
        parent::__construct();
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
            $this->afterBuild();
            return $this->entity;
        }
        return null;
    }

    public function afterBuild()
    {
        $systemRepository = resolve(EntityRepository::class);

        return $systemRepository->save(
            $this->entity
        );
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