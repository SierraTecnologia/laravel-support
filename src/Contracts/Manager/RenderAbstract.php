<?php
namespace Support\Contracts\Manager;

use Support\Contracts\Support\Arrayable;
use Support\Contracts\Support\ArrayableTrait;
use Support\Traits\Debugger\HasErrors;
use Support\Traits\Coder\GetSetTrait;
use Support\Contracts\Output\OutputableTrait;
use Illuminate\Database\Eloquent\Collection;

abstract class RenderAbstract extends ManagerAbstract implements RenderInterface
{
    /**
     * Atributos
     */
    use GetSetTrait;

    /**
     * Identify ClassName
     *
     * @var          string
     * @getter       true
     * @setter       true
     * @serializable true
     */
    protected $parameter = '';

    /**
     * Identify ClassName
     *
     * @var          \Illuminate\Database\Eloquent\Collection
     * @getter       true
     * @setter       true
     * @serializable true
     */
    protected $childrens = [];

    /**
     * Identify ClassName
     *
     * @var          \Illuminate\Database\Eloquent\Collection
     * @getter       true
     * @setter       true
     * @serializable true
     */
    protected $childrenRenders = [];

    public function __invoke()
    {
        return $this->data;
        // return $this->getChildrens();
    }

    /*
        Constructor
    */ 

    public function __construct($parameter = '', $output = false)
    {
        $this->parameter = $parameter;
        parent::__construct($output);
    }

    // abstract function render();

    public function run()
    {
        $this->info('Rodando Render: '.static::class);
        $this->generateChildrens();
        $this->runChildrens();
        
        $this->generateData();
        return true;
    }
    public function generateChildrens()
    {
        $model = $this->setChildrens(
            new Collection($this->renderChildrens())
        );
        
        return true;
    }

    public function generateData()
    {
        return $this->data = $this->renderData();
    }


    /**
     * Chieldrens
     */
    public function runChildrens()
    {
        $results = $this->getChildrens();
        foreach($results as $result) {
            $this->runForChildren($result);
        }
        
        return true;
    }
    public function runForChildren($children)
    {
        $this->info('Rodando Render Filho: '.$children);
        $forChieldren = static::$renderForChildrens;
        if ($forChieldren) {
            $this->childrenRenders[$children] = $forChieldren::make($children);
        }
    }

    // public function makeModel()
    // {
    //     $model = $this->app->make($this->model());
    //     if(!$model instanceof Model) {
    //         // Throw a a repository exception
    //         return 'error';
    //     }

    //     return $this->model = $model;
    // }

}