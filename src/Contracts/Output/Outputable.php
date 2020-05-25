<?php

namespace Support\Contracts\Output;

use Illuminate\Contracts\Support\Arrayable as BaseArrayable;

interface Outputable
{
    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function info();
}