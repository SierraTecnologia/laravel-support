<?php

namespace Support\Contracts\Support;

use Illuminate\Contracts\Support\Arrayable as BaseArrayable;

interface Arrayable extends BaseArrayable
{
    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray();

    /**
     * Set the instance as an array.
     *
     * @return array
     */
    public function fromArray(Array $array);
}