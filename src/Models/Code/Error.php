<?php

namespace Support\Models\Code;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Support\Components\Database\Schema\SchemaManager;
use Facilitador\Facades\Facilitador;
use Facilitador\Traits\Translatable;
use Facilitador\Services\ModelService;

class Error extends Model
{
    public $timestamps = true;

    protected $table = 'errors';
    // protected $primaryKey = 'class_name';
    // protected $keyType = 'string';

    protected $fillable = [
        'name',
        'class_type',
        'target',
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
