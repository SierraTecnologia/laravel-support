<?php

namespace Support\Models;

/**
 * Support\Models\ApiCallsCount
 *
 * @property int $id
 * @property string $url
 * @property int|null $user_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Support\Models\ApiCallsCount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Support\Models\ApiCallsCount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Support\Models\ApiCallsCount whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Support\Models\ApiCallsCount whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Support\Models\ApiCallsCount whereUserId($value)
 * @mixin \Eloquent
 */
class ApiCallsCount extends \Eloquent
{
    public $table = 'api_calls_count';

    protected $fillable = [
        'url'
    ];
}