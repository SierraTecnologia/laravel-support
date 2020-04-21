<?php

namespace Support\Models;

use Illuminate\Database\Eloquent\Model;
use Facilitador\Traits\Translatable;

class DataRelationship extends Model
{
    protected $table = 'data_relations';

    protected $guarded = [];

    public $timestamps = false;
    
    protected $primaryKey = 'code';
    protected $keyType = 'string';

    protected $fillable = [
        'table_name',
        'table_name_inverse',
        'relation_type',
        'relation_type_inverse',
        'data',
    ];


    public function setDataAttribute($value)
    {
        $this->attributes['data'] = json_encode($value);
    }

    public function getDataAttribute($value)
    {
        return json_decode(!empty($value) ? $value : '{}');
    }

}
