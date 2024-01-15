<?php

namespace SiObjects\Components\Book;

use MediaManager\Models\Model;

/**
 * Class Attribute
 *
 * @package App
 */
class Tag extends Model
{
    protected $fillable = ['name', 'value', 'order'];

    /**
     * Get the entity that this tag belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function entity()
    {
        return $this->morphTo('entity');
    }
}
