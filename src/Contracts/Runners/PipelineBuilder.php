<?php

namespace Support\Contracts\Runners;

use League\Pipeline\PipelineBuilder as PipelineBuilderBase;
use League\Pipeline\PipelineInterface;
use League\Pipeline\ProcessorInterface;
use League\Pipeline\PipelineBuilderInterface;
use Support\Contracts\Support\Arrayable;
use Support\Contracts\Support\ArrayableTrait;
use Support\Traits\Debugger\HasErrors;
use Support\Traits\Coder\GetSetTrait;
use Support\Contracts\Output\OutputableTrait;
use Illuminate\Database\Eloquent\Collection;

class PipelineBuilder implements PipelineBuilderInterface
{
    use HasErrors, ArrayableTrait, OutputableTrait;
    /**
     * @var callable[]
     */
    private $stages = [];

    /**
     * @return self
     */
    public function add(callable $stage): PipelineBuilderInterface
    {
        $this->stages[] = $stage;

        return $this;
    }

    public function build(ProcessorInterface $processor = null): PipelineInterface
    {
        return Pipeline::makeWithOutput($this->getOutput(), $processor, ...$this->stages);
    }
}