<?php

declare(strict_types=1);

namespace Support\Utils\Modificators;

use Log;
use ArgumentCountError;
use Symfony\Component\Inflector\Inflector;
use Illuminate\Support\Str;
use Cocur\Slugify\Slugify;

class StringModificator
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
    public static function plurarizeAndLower($name)
    {
        return strtolower(
            self::plurarize($name)
        );
    }

    public static function singularize($name)
    {
        $name = Inflector::singularize($name);
        if (is_array($name)) {
            $name = $name[count($name) - 1];
        }
        return $name;
    }
    public static function singularizeAndLower($name)
    {
        return strtolower(
            self::singularize($name)
        );
    }



    /**
     * 
     */
    
    public static function cleanCodeSlug($slug)
    {
        $slugify = new Slugify();
        
        $slug = $slugify->slugify($slug, '.'); // hello-world
        
        return $slug;
    }
    public static function convertSlugToName($slug)
    {
        return collect(explode('.', static::cleanCodeSlug($slug)))->map(
            function ($namePart) {
                return ucfirst($namePart);
            }
        )->implode(' ');
    }
}
