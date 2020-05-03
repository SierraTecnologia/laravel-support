<?php

declare(strict_types=1);

namespace Support\ClassesHelpers\Extratores;

use Log;
use ArgumentCountError;
use Symfony\Component\Inflector\Inflector;
use Illuminate\Support\Str;

class StringExtractor
{


    public static function plurarize($name)
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

    public static function singularize($name)
    {
        $name = Inflector::singularize($name);
        if (is_array($name)) {
            $name = $name[count($name) - 1];
        }
        return $name;
    }
}
