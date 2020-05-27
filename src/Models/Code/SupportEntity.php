<?php
/**
 * Cache dos Entitys
 */

namespace Support\Models\Code;

use Illuminate\Database\Eloquent\Model;
class SupportEntity extends Model
{
    public $timestamps = false;

    protected $table = 'support_code_entitys';
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
