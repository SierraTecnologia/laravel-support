<?php
namespace Support\Helps;


/**
 * Array helper.
 */
class CodeFileHelper
{
    
    public static function getClassName($class)
    {
        $class = explode("\\", self::getFullClassName($class));
        return $class[count($class)-1];
    }
    
    public static function getFullClassName($class)
    {
        return get_class($class);
    }

}