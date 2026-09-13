<?php

namespace Support\Http\Controllers\RiCa;

use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class WildcardController
{
    public function __invoke()
    {
        $response = app('facilitador.wildcard')->detectAndExecute();

        if ($response instanceof Response || $response instanceof View) {
            return $response;
        }

        abort(404);
    }
}
