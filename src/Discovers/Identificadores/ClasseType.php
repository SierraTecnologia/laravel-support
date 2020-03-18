<?php

namespace Support\Discovers\Identificadores;

use ReflectionClass;

class ClasseType
{
    public $className = false;
    public $type = false;

    public static $types = [
        'model' => 'Illuminate\Database\Eloquent\Model',
    ];

    public static function fastExecute($class, $method, ...$args)
    {
        return (new static($class))->$method(...$args);
    }

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
        // Verify if is Abstract
        if ($this->getReflectionClass()->isAbstract()) {
            return 'abstract';
        }

        foreach (static::$types as $type => $subClassName) {
            // Detected
            if (is_subclass_of($this->className, $subClassName)) {
                return $type;
            }
        }

        return 'other';
    }

    public function getReflectionClass()
    {
        return new ReflectionClass($this->className);
    }
}
