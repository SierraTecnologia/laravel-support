<?php

declare(strict_types=1);

namespace Muleta\Utils\Modificators;

use Log;
use ArgumentCountError;
use Symfony\Component\Inflector\Inflector;
use Illuminate\Support\Str;
use Cocur\Slugify\Slugify;

class StringModificator
{

    public static function tirarAcentos($string): ?string
    {
        return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/","/(ç)/","/(Ç)/"), explode(" ", "a A e E i I o O u U n N c C"), $string);
    }


    public static function plurarize(string $name): string
    {
        // return Str::plural(Str::lower($name));
        return Str::plural($name);

        // $name = Inflector::pluralize($name);
        // if (is_array($name)) {
        //     $name = $name[count($name) - 1];
        // }
        // return $name;
    }
    public static function plurarizeAndLower($name): string
    {
        return Str::lower(
            self::plurarize($name)
        );
    }

    public static function singularize($name): string
    {
        return Str::singular($name);
        // $name = Inflector::singularize($name);
        // if (is_array($name)) {
        //     $name = $name[count($name) - 1];
        // }
        // return $name;
    }
    public static function singularizeAndLower($name): string
    {
        return Str::lower(
            self::singularize($name)
        );
    }



    /**
     * @return string
     */
    public static function clean($word): string
    {
        $remove = [
            '"'
        ];

        return $word = \str_replace($remove, '', $word);

        // return self::singularize($word);
    }
    
    public static function cleanCodeSlug($slug): string
    {
        // $slugify = new Slugify();
        // $slug = $slugify->slugify($slug, '.'); // hello-world

        $slug = Str::kebab($slug);
        $slug = Str::slug($slug, '.');
        
        return $slug;
    }
    public static function convertSlugToName($slug): string
    {
        return collect(explode('.', static::cleanCodeSlug($slug)))->map(
            function ($namePart) {
                return ucfirst($namePart);
            }
        )->implode(' ');
    }
}
