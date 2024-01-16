<?php

namespace Muleta\Contracts\Support;

use Illuminate\Contracts\Support\Jsonable as BaseJsonable;

interface Jsonable extends BaseJsonable
{
    /**
     * Convert the object to its JSON representation.
     *
     * @param  int $options
     * @return string
     */
    public function toJson($options = 0);
}