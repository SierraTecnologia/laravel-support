<?php

declare(strict_types=1);

namespace Support\Entities\Type;

use Facilitador\Models\Value;

/**
 * Support\Entities\Type\Boolean.
 *
 * @property int                                                $id
 * @property bool                                               $content
 * @property int                                                $attribute_id
 * @property int                                                $entity_id
 * @property string                                             $entity_type
 * @property \Carbon\Carbon|null                                $created_at
 * @property \Carbon\Carbon|null                                $updated_at
 * @property-read \Facilitador\Models\Attribute           $attribute
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $entity
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Support\Entities\Type\Boolean whereAttributeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Support\Entities\Type\Boolean whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Support\Entities\Type\Boolean whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Support\Entities\Type\Boolean whereEntityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Support\Entities\Type\Boolean whereEntityType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Support\Entities\Type\Boolean whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Support\Entities\Type\Boolean whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Boolean extends Value
{
    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'content' => 'boolean',
        'attribute_id' => 'integer',
        'entity_id' => 'integer',
        'entity_type' => 'string',
    ];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('facilitador.attributes.tables.attribute_boolean_values'));
        $this->setRules([
            'content' => 'required|boolean',
            'attribute_id' => 'required|integer|exists:'.config('facilitador.attributes.tables.attributes').',id',
            'entity_id' => 'required|integer',
            'entity_type' => 'required|string',
        ]);
    }
}
