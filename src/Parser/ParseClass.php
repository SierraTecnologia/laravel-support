<?php

declare(strict_types=1);


namespace Support\Parser;

use App;
use Log;
use Exception;
use Support\Models\Code\Classes;

class ParseClass
{
    public $supportModelCodeClass = false;

    public $reflectionClass = false;
    public $className = false;
    public $parentClass = false;
    public $fileName = false;
    public $type = false;

    public static $types = [
        'model' => 'Illuminate\Database\Eloquent\Model',
    ];

    // Tudo em minusculo
    public static $typesIgnoreName = [
        'model' => [
            'model',
            'base'
        ],
    ];

    public function __construct($classOrReflectionClass)
    {
        $this->className = $classOrReflectionClass;
        $this->type = $this->detectType();
        if (!$this->supportModelCodeClass = Classes::find($this->className)) {
            $this->supportModelCodeClass = new Classes;
            $this->supportModelCodeClass->class_name = $this->getClasseName();
            $this->supportModelCodeClass->filename = $this->getClassFilename();
            $this->supportModelCodeClass->parent_class = $this->getParentClassName();
            $this->supportModelCodeClass->type = $this->getType();
            $this->supportModelCodeClass->data = $this->toArray();
            $this->supportModelCodeClass->save();
        } else {
            $this->fromArray($this->supportModelCodeClass->data);
        }
        // @debug
        // dd($this->reflectionClass->getProperties());
        // dd($this->reflectionClass->getMethods());

        // dd(
        //     $this->reflectionClass->getProperty('fillable'),
        //     $this->reflectionClass->getProperty('primaryKey'),
        //     // $this->reflectionClass->getProperty('cast'),
        //     $this->reflectionClass->getProperty('table')
        // );
        // var_dump($this->reflectionClass->getFileName());
    }
    
    public function getReflectionClassForUse()
    {
        if (!$this->reflectionClass) {
            $this->reflectionClass = static::getReflectionClass($this->className);
        }
        return $this->reflectionClass;
    }

    public function toArray()
    {
        return [

            'class' => $this->getClasseName(),
            'filename' => $this->getClassFilename(),
            'parentClass' => $this->getParentClassName(),
            'interfaces' => $this->getInterfaceNames(),
            'type' => $this->getType(),

        ];

    }

    public function fromArray($array)
    {
        if (isset($array['class'])) {
            $this->setClasseName($array['class']);
        }
        if (isset($array['filename'])) {
            $this->setClassFilename($array['filename']);
        }
        if (isset($array['parentClass'])) {
            $this->setParentClassName($array['parentClass']);
        }
        // if (isset($array['interfaces'])) {
        //     $this->setInterfaceNames($array['interfaces']);
        // }
        if (isset($array['type'])) {
            $this->setType($array['type']);
        }
    }

    // @todo fazer getSetter para cada um desses
    public function getClasseName()
    {
        return $this->className;
    }
    public function setClasseName()
    {
        $this->className = $className;
    }
    public function getParentClassName()
    {
        if ($this->parentClass === false) {
            if (!$parentClass = $this->getReflectionClassForUse()->getParentClass()){
                $this->parentClass = null;
            } else {
                $this->parentClass = $parentClass->getName();
            }
        }
        return $this->parentClass;
    }
    public function setParentClassName($parentClass)
    {
        $this->parentClass = $parentClass;
    }
    public function getClassFilename()
    {
        if ($this->fileName === false) {
            $this->fileName = $this->getReflectionClassForUse()->getFileName();
        }
        return $this->fileName;
    }
    public function setClassFilename($fileName)
    {
        $this->fileName = $fileName;
    }
    public function getType()
    {
        return $this->type;
    }
    public function setType($type)
    {
        $this->type = $type;
    }
    public function getInterfaceNames()
    {
        return $this->getReflectionClassForUse()->getInterfaceNames();
    }


    /**
     * Outras Funcoes
     */
    public function typeIs($type)
    {
        return $this->type == $type;
    }

    protected function detectType()
    {
        // Verify if is Interface
        if ($this->getReflectionClassForUse()->isInterface()) {
            return 'interface';
        }
        // Verify if is Abstract
        if ($this->getReflectionClassForUse()->isAbstract()) {
            return 'abstract';
        }

        foreach (static::$types as $type => $subClassName) {
            // Detected
            if (is_subclass_of($this->className, $subClassName)) {
                if (!isset(static::$typesIgnoreName[$type]) || !in_array(static::getClassName($this->className), static::$typesIgnoreName[$type])) {
                    // dd(static::$typesIgnoreName, static::getClassName($this->className), $this->className);
                    return $type;
                }
            }
        }

        return 'other';
    }
    
    public static function getFileName($classOrReflectionClass = false)
    {
        return (static::getReflectionClass($classOrReflectionClass))->getFileName();
    }

    /**
     * Gets the class name.
     * @return string
     */
    public static function getClassName($class)
    {
        return strtolower(array_slice(explode('\\', $class), -1, 1)[0]);
    }

    public static function returnInstanceForClass($class, $with = false)
    {

        if (is_object($class)) {
            return $class;
        }

        if (!class_exists($class)) {
            Log::warning('[Support] Code Parser -> Class não encontrada no ModelService -> ' . $class);
            throw new Exception('Class não encontrada no ModelService' . $class);
        }
        
        if ($with) {
            return with(new $class);
        }
        return App::make($class);
    }






    protected static function getReflectionClass($classOrReflectionClass = false)
    {
        if (!$classOrReflectionClass || is_string($classOrReflectionClass)) {
            $classOrReflectionClass = new \ReflectionClass($classOrReflectionClass);
        }
        return $classOrReflectionClass;
    }

    /**
     * Veio da outra classe que eu tinha feito antes
     */
    public static function fastExecute($class, $method, ...$args)
    {
        return (new static($class))->$method(...$args);
    }

}