<?php

namespace Support\Http;

use Pedreiro\Http\MenuFilter as MenuFilterBase;

class MenuFilter extends MenuFilterBase
{
    public function transform($item)
    {
        return parent::transform($item);
    }
}
