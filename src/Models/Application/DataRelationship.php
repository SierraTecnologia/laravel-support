<?php

namespace Support\Models\Application;

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
        'origin_table_name',
        'origin_table_class',
        'origin_foreignKey',
    
        'related_table_name',
        'related_table_class',
        'related_foreignKey',
    
        // Morph
        'morph_id',
        'morph_type',
        'is_inverse',
    
        // Others Values
        'pivot',
    
        'name',
        'type',
        'model',
        'foreignKey',
        'ownerKey',

        // Old

        // 'table_name',
        // 'table_name_inverse',
        // 'relation_type',
        // 'relation_type_inverse',
        // 'data',
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
