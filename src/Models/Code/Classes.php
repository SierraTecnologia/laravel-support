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
    ];

    protected $modelService = false;

    public static function getFinalClass($className)
    {
        while ($result = self::where('parent_class', $className)->first()) {
            $className = $result->class_name;
        }
        return $className;
    }
}
