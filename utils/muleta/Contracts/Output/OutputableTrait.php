<?php

namespace Muleta\Contracts\Output;

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
    public function debug($message)
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
    public function notice($message)
    {
        if ($this->output) {
            $this->output->info($message);
        }
    }
    public function warning($message)
    {
        if ($this->output) {
            $this->output->warning($message);
        }
    }
    public function error($message)
    {
        if ($this->output) {
            $this->output->error($message);
        }
    }

    public function addOutput($output)
    {
        $this->output = $output;
    }

    public function getOutput()
    {
        return $this->output;
    }

    public static function make(...$parameters)
    {
        return new static(...$parameters);
    }

    public static function makeWithOutput($output, ...$parameters)
    {
        $instance = static::make(...$parameters);
        $instance->addOutput($output);
        return $instance;
    }

}
