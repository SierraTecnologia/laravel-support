<?php

namespace Muleta\Modules\Eloquents\Displays;

interface ResourceInterface
{

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request);

}