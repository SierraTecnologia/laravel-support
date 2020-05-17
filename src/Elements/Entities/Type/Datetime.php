<?php

declare(strict_types=1);

namespace Support\Elements\Entities\Type;

use Facilitador\Models\Value;

/**
 * Support\Elements\Entities\Type\Datetime.
 *
 * @property      int                                                $id
 * @property      \Carbon\Carbon                                     $content
 * @property      int                                                $attribute_id
 * @property      int                                                $entity_id
 * @property      string                                             $entity_type
 * @property      \Carbon\Carbon|null                                $created_at
 * @property      \Carbon\Carbon|null                                $updated_at
 * @property-read \Facilitador\Models\Attribute           $attribute
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $entity
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Support\Elements\Entities\Type\Datetime whereAttributeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Support\Elements\Entities\Type\Datetime whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Support\Elements\Entities\Type\Datetime whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Support\Elements\Entities\Type\Datetime whereEntityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Support\Elements\Entities\Type\Datetime whereEntityType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Support\Elements\Entities\Type\Datetime whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Support\Elements\Entities\Type\Datetime whereUpdatedAt($value)
 * @mixin  \Eloquent
 */
class Datetime extends Value
{
    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'content' => 'datetime',
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

        $this->setTable(\Illuminate\Support\Facades\Config::get('sitec.attributes.tables.attribute_datetime_values'));
        $this->setRules(
            [
            'content' => 'required|date',
            'attribute_id' => 'required|integer|exists:'.\Illuminate\Support\Facades\Config::get('sitec.attributes.tables.attributes').',id',
            'entity_id' => 'required|integer',
            'entity_type' => 'required|string',
            ]
        );
    }
}
