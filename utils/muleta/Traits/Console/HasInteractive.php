<?php

declare(strict_types=1);

namespace Muleta\Traits\Console;

trait HasInteractive
{

    public $console = false;

    /**
     * Console
     */
    public function send($text, $type = 'info')
    {
        if ($this->console) {
            $this->console->info($text);
        }
    }
    public function table($headers, $data)
    {
        if ($this->console) {
            $this->console->table($headers, $data);
        }
    }
    public function setInteractive($console)
    {
        $this->console = $console;
    }


    public function choice($question, $data)
    {
        if ($this->console) {
            return $this->console->choice($question, $data);
        }
    }
    public function anticipate($question, $data)
    {
        if ($this->console) {
            return $this->console->anticipate($question, $data);
        }
    }
    public function confirm($question, $data)
    {
        if ($this->console) {
            return $this->console->confirm($question, $data);
        }
    }

}
