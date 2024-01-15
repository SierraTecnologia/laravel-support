<?php

namespace Muleta\Contracts\Support;

use Illuminate\Contracts\Support\Htmlable as BaseHtmlable;

interface Htmlable extends BaseHtmlable
{
    /**
     * Get content as a string of HTML.
     *
     * @return string
     */
    public function toHtml();
}