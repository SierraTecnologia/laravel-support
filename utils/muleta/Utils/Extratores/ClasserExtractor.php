<?php

declare(strict_types=1);

namespace Muleta\Utils\Extratores;

use App;
use Log;
use Exception;

class ClasserExtractor
{
    


    /**
     * @return string[]
     *
     * @psalm-return non-empty-list<string>
     */
    public static function getNamespace($className): array
    {
        // $namespaceWithoutModels = explode("Models\\", $this->className);
        // return join(array_slice(explode("\\", $namespaceWithoutModels[1]), 0, -1), "\\");
        return explode("\\", $className);
    }
    public static function getPackageNamespace($className): string
    {
        return self::getNamespace($className)[0];
    }
    
    public static function getFileFromClass($class)
    {
        return self::getFileName(get_class($class));
    }

    
    /**
     * @todo tirar daqui
     *
     * @param false|get-class-of<$class> $classOrReflectionClass
     */
    public static function getFileName($classOrReflectionClass = false)
    {
        return (static::getReflectionClass($classOrReflectionClass))->getFileName();
    }

    
    /**
     * @todo tirar daqui
     */
    /**
     * Gets the class name.
     *
     * @return string
     */
    public static function getClassName($class)
    {
        return strtolower(array_slice(explode('\\', $class), -1, 1)[0]);
    }

    
    /**
     * @todo tirar daqui
     */
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






    
    /**
     * @todo tirar daqui
     */
    public static function getReflectionClass($classOrReflectionClass = false)
    {
        if (!$classOrReflectionClass || is_string($classOrReflectionClass)) {
            $classOrReflectionClass = new \ReflectionClass($classOrReflectionClass);
        }
        return $classOrReflectionClass;
    }
}
