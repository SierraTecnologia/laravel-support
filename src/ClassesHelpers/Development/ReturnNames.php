<?php

declare(strict_types=1);

namespace Support\ClassesHelpers\Development;

use Log;
use ArgumentCountError;
use Symfony\Component\Inflector\Inflector;


class ReturnNames
{
    /**
     * Retorna Nome no Singular caso nao exista, e minusculo
     */
    public static function returnNameIfNotExistInArray($string, $array, $local)
    {
        try {
            $cod = '$array'.\str_replace('{{index}}', $string, $local);
            \Log::info('Executando: '.$cod);
            return eval($array);
        } catch (\Exception $th) {
            // @todo
            $stringQuebrada = explode('\\', $string);
            \Log::info('Uhul: '.strtolower(self::plurarize($stringQuebrada[count($stringQuebrada)-1])));
            return strtolower(self::plurarize($stringQuebrada[count($stringQuebrada)-1]));
        }
    }

    public static function plurarize($name)
    {
        $name = Inflector::pluralize($name);
        if (is_array($name)) {
            $name = $name[count($name) - 1];
        }
        return $name;
    }

    public static function singuralize($name)
    {
        $name = Inflector::pluralize($name);
        if (is_array($name)) {
            $name = $name[count($name) - 1];
        }
        return $name;
    }
}
