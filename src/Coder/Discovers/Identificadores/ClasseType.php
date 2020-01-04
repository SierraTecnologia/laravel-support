<?php

namespace Support\Coder\Discovers\Identificadores;

class ClasseType
{
    public $className = false;
    public $type = false;

    public static $types = [
        'model' => 'Illuminate\Database\Eloquent\Model',
    ];

    public function __construct($className)
    {
        $this->className = $className;
        $this->type = $this->detectType();
    }

    public function typeIs($type)
    {
        return $this->type == $type;
    }

    protected function detectType()
    {
        foreach (static::$types as $type => $subClassName) {
            if (!is_subclass_of($this->className, $subClassName)) {
                return $type;
            }
        }

        return 'other';
    }
}
