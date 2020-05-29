<?php

declare(strict_types=1);

namespace Facilitador\Models;

use Illuminate\Database\Eloquent\Model;
use Support\Recursos\Cacheable\CacheableEloquent;

/**
 * Facilitador\Models\AttributeEntity.
 *
 * @property int                 $attribute_id
 * @property string              $entity_type
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Facilitador\Models\AttributeEntity whereAttributeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Facilitador\Models\AttributeEntity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Facilitador\Models\AttributeEntity whereEntityType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Facilitador\Models\AttributeEntity whereUpdatedAt($value)
 * @mixin  \Eloquent
 */
class AttributeEntity extends Model
{
    use CacheableEloquent;

    /**
     * {@inheritdoc}
     */
    protected $table = 'attribute_entity';

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'entity_type',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'entity_type' => 'string',
    ];
}
