<?php

namespace Support\Contracts\Output;

/**
 * Allows the registering of transforming callbacks that get applied when the
 * class is serialized with toArray() or toJson().
 */
trait OutputableTrait
{
    /**
     * Identify ClassName
     *
     * @var          string
     * @getter       true
     * @setter       true
     * @serializable true
     */
    protected $output = false;

    public function message($message)
    {
        if ($this->output) {
            $this->output->info($message);
        }
    }
    public function info($message)
    {
        if ($this->output) {
            $this->output->info($message);
        }
    }

}
