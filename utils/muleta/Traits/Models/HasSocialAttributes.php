<?php

declare(strict_types=1);

namespace Muleta\Traits\Models;

use Illuminate\Database\Eloquent\Builder;
use Spatie\SchemalessAttributes\SchemalessAttributes;

trait HasSocialAttributes
{
    /**
     * Get social attributes.
     *
     * @return \Spatie\SchemalessAttributes\SchemalessAttributes
     */
    public function getSocialAttribute(): SchemalessAttributes
    {
        return SchemalessAttributes::createForModel($this, 'social');
    }

    /**
     * Scope with social attributes.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithSocial(): Builder
    {
        return SchemalessAttributes::scopeWithSchemalessAttributes('social');
    }
}
