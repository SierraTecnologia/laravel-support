<?php

namespace Muleta\Channels\Messages;

use Illuminate\Support\Arr;

class SmsMessage
{
    public $from;
    public $to;
    public $msg;
    public $id;
    public $schedule;
    public $callbackOption;
    public $flashSms;

    /**
     * @return static
     */
    public static function create($msg = null): self
    {
        return new static($msg);
    }

    public function __construct($msg = null)
    {
        $this->msg($msg);
    }

    /**
     * @return static
     */
    public function from($from): self
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @return static
     */
    public function to($to): self
    {
        $this->to = $to;
        return $this;
    }

    /**
     * @return static
     */
    public function msg($msg): self
    {
        $this->msg = $msg;
        return $this;
    }

    /**
     * @return static
     */
    public function content($content): self
    {
        $this->msg($content);
        return $this;
    }

    /**
     * @return static
     */
    public function id($id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return static
     */
    public function schedule($schedule): self
    {
        $this->schedule = $schedule;
        return $this;
    }

    /**
     * @return static
     */
    public function callbackOption($callbackOption): self
    {
        $this->callbackOption = $callbackOption;
        return $this;
    }

    /**
     * @return static
     */
    public function flashSms($flashSms): self
    {
        $this->flashSms = $flashSms;
        return $this;
    }

    /**
     * @return array
     *
     * @psalm-return array{from: mixed, to: mixed, msg: mixed, id: mixed, schedule: mixed, callbackOption: mixed, flashSms: mixed}
     */
    public function toArray(): array
    {
        return [
            'from'           => $this->from,
            'to'             => $this->to,
            'msg'            => $this->msg,
            'id'             => $this->id,
            'schedule'       => $this->schedule,
            'callbackOption' => $this->callbackOption,
            'flashSms'       => $this->flashSms,
        ];
    }
}
