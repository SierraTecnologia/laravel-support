<?php

namespace Facilitador\Collections;

use Illuminate\Database\Eloquent\Collection;
use Support\Traits\Models\SerializeWithImages;
use Support\Traits\Models\CanSerializeTransform;

/**
 * The collection that is returned from queries on models that extend from
 * Decoy's base model.  Adds methods to tweak the serialized output
 */
class Base extends Collection
{
    use CanSerializeTransform,
        SerializeWithImages;
}
