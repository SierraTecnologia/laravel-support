<?php
/**
 * Cache dos Entitys
 */

namespace Support\Models\App;

use Illuminate\Database\Eloquent\Model;
class System extends Model
{
    public $timestamps = false;

    protected $table = 'support_app_systems';
    protected $primaryKey = 'code';
    protected $keyType = 'string';

    protected $fillable = [
        'code',
        'type',
        'parameter',
        'md5',
        'data',
    ];


    public function setDataAttribute($value)
    {
        $this->attributes['data'] = json_encode($value);
    }

    public function getDataAttribute($value)
    {
        return json_decode(!empty($value) ? $value : '{}', true);
    }
}
