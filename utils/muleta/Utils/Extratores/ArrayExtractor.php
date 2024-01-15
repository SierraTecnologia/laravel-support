<?php

declare(strict_types=1);

namespace Muleta\Utils\Extratores;

use Log;
use ArgumentCountError;
use Symfony\Component\Inflector\Inflector;
use Illuminate\Database\Eloquent\Relations\Relation;
use Muleta\Utils\Modificators\StringModificator;

use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\DBALException;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Symfony\Component\Debug\Exception\FatalErrorException;
use Exception;
use ErrorException;
use LogicException;
use OutOfBoundsException;
use RuntimeException;
use TypeError;
use Throwable;
use Watson\Validating\ValidationException;
use Illuminate\Contracts\Container\BindingResolutionException;

class ArrayExtractor
{
    
    /**
     * Retorna Nome no Singular caso nao exista, e minusculo
     */
    public static function returnNameIfNotExistInArray($indexString, $array, $local)
    {
        try {
            $cod = '$toReturn = $array'.\str_replace('{{index}}', '\''.$indexString.'\'', $local).';';
            \Log::debug('ArrayExtractor: Executando: '.$cod);
            // dd(
            //     $cod
            // );
            eval($cod);
            return $toReturn;
        } catch(LogicException|ErrorException|RuntimeException|OutOfBoundsException|TypeError|ValidationException|FatalThrowableError|FatalErrorException|Exception|Throwable  $th) {
            // dd(
            //     'Problema ArrayExtractor1',
            //     $th->getMessage()
            // );
            try {
                // @todo
                $stringQuebrada = explode('\\', $indexString);
                \Log::debug('ArrayExtractor: Retornando nome pois a classe não existe: '.strtolower(StringModificator::plurarize($stringQuebrada[count($stringQuebrada)-1])));
                return strtolower(StringModificator::plurarize($stringQuebrada[count($stringQuebrada)-1]));
            } catch(LogicException|ErrorException|RuntimeException|OutOfBoundsException|TypeError|ValidationException|FatalThrowableError|FatalErrorException|Exception|Throwable  $e) {
                dd(
                    'Problema ArrayExtractor',
                    $e->getMessage()
                );
            }
        }

        return false;
    }


}
