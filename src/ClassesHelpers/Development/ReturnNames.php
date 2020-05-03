<?php

declare(strict_types=1);

namespace Support\ClassesHelpers\Development;

use Log;
use ArgumentCountError;
use Symfony\Component\Inflector\Inflector;
use Illuminate\Support\Str;

class ReturnNames
{

    public static function plurarize($name)
    {
        $name = Inflector::pluralize($name);
        if (is_array($name)) {
            $name = $name[count($name) - 1];
        }
        return $name;
    }

    public static function singularize($name)
    {
        /**
         *  $method = Str::plural(Str::lower(class_basename($model)));
         */
        $name = Inflector::pluralize($name);
        if (is_array($name)) {
            $name = $name[count($name) - 1];
        }
        return $name;
    }
}
