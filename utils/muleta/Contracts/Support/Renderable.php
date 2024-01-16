<?php

namespace Muleta\Contracts\Support;

use Illuminate\Contracts\Support\Renderable as BaseRenderable;

interface Renderable extends BaseRenderable
{
    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render();
}
