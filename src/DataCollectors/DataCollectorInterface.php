<?php

namespace Support\DataCollectors;

use Illuminate\Http\Request;

interface DataCollectorInterface
{
    public function collect(Request $request);
}