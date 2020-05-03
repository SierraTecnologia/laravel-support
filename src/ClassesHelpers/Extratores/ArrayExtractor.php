<?php

declare(strict_types=1);

namespace Support\ClassesHelpers\Extratores;

use Log;
use ArgumentCountError;
use Symfony\Component\Inflector\Inflector;


class ArrayExtractor
{
    /**
     * Retorna Nome no Singular caso nao exista, e minusculo
     */
    public static function returnNameIfNotExistInArray($string, $array, $local)
    {
        try {
            $cod = '$array'.\str_replace('{{index}}', $string, $local);
            \Log::info('ReturnNames: Executando: '.$cod);
            return eval($array);
        } catch (\Exception $th) {
            // @todo
            $stringQuebrada = explode('\\', $string);
            \Log::info('ReturnNames: Retornando nome pois a classe não existe: '.strtolower(self::plurarize($stringQuebrada[count($stringQuebrada)-1])));
            return strtolower(self::plurarize($stringQuebrada[count($stringQuebrada)-1]));
        }
    }
}
