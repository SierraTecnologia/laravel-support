<?php

namespace Support\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Pedreiro\Models\Base as Model;
use Support\Models\SortableTrait;

abstract class Base extends Model
{
    use Sluggable,
        SluggableScopeHelpers,
        SortableTrait;
}
