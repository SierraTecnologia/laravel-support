<?php

declare(strict_types=1);


namespace Support\Parser;

use App;

class ParseClass
{

    public $reflectionClass = false;

    public function __construct($classOrReflectionClass)
    {
        $this->reflectionClass = static::getReflectionClass($classOrReflectionClass);
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
            Log::warning('[Support] Code Parser -> Class não encontrada no ModelService' . $class);
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
}