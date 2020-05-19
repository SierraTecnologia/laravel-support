<?php

namespace Support\Models\Code;

use Illuminate\Database\Eloquent\Model;
class Classes extends Model
{
    public $timestamps = false;

    protected $table = 'support_code_classes';
    protected $primaryKey = 'class_name';
    protected $keyType = 'string';

    protected $fillable = [
        'class_name',
        'filename',
        'parent_class',
        'type',
        'data',
    ];

    protected $modelService = false;

    /**
     * Retorna a ultima classe que chama
     */
    public static function getFinalClass($className)
    {
        while ($result = self::where('parent_class', $className)->first()) {
            $className = $result->class_name;
        }
        return $className;
    }

    public function setDataAttribute($value)
    {
        $this->attributes['data'] = json_encode($value);
    }

    public function getDataAttribute($value)
    {
        return json_decode(!empty($value) ? $value : '{}', true);
    }
}
