<?php

namespace Support\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Pedreiro\Models\Base as Model;
use Muleta\Traits\Models\SortableTrait;

abstract class Base extends Model
{
    use Sluggable,
        SluggableScopeHelpers,
        SortableTrait;
}
