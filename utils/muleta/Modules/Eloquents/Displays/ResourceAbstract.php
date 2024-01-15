<?php

namespace Muleta\Modules\Eloquents\Displays;

use Illuminate\Http\Resources\Json\JsonResource;

abstract class ResourceAbstract extends JsonResource implements ResourceInterface
{
    public $with = [
        'success' => true
    ];
}