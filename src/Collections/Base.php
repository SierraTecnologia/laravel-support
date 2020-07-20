<?php

namespace Support\Collections;

use Illuminate\Database\Eloquent\Collection;
use Muleta\Traits\Models\SerializeWithImages;
use Muleta\Traits\Models\CanSerializeTransform;

/**
 * The collection that is returned from queries on models that extend from
 * Facilitador's base model.  Adds methods to tweak the serialized output
 */
class Base extends Collection
{
    use CanSerializeTransform,
        SerializeWithImages;

}
