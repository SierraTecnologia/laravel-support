<?php

declare(strict_types=1);

namespace Muleta\Utils\Searchers;

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

class ArraySearcher
{
    /**
     * @return (int|string)[]|false
     *
     * @psalm-return false|non-empty-list<array-key>
     */
    public static function arrayIsearch($str, array $array)
    {
        if (empty($str) || is_null($str) || $str===false) {
            return false;
        }
        if (!is_array($array)) {
            dd(
                'ErroAquiPut',
                $str, $array
            );
            return false;
        }
        $found = array();
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                if ($subFound = self::arrayIsearch($str, $v)) {
                    // dd(
                    //     'Debug ArrayExtarctor',
                    //     $subFound,
                    //     $str, $v
                    // );
                    // foreach ($subFound as $sub) {
                    // }
                    $found[] = $k;
                }
            } else {
                if (is_array($str)) {
                    if (self::arrayIsearch($v, $str)) {
                        $found[] = $k;
                    }
                } else
                // Caso seja inteiro, não transforma em minuscula pra nao dar problema
                if (!empty($v) && !is_null($v) && (is_int($v) || is_int($str)) && ($v === $str)) {
                    $found[] = $k;
                } else if (!empty($v) && !is_null($v) && strtolower($v) == strtolower($str)) {
                    $found[] = $k;
                }
            }
        }

        if (empty($found)) {
            return false;
        }

        return $found;
    }

    /**
     * @return array|false
     *
     * @psalm-return false|non-empty-list<mixed>
     */
    public static function arraySearchByAttribute($str, array $array, $attribute)
    {
        if (empty($str) || is_null($str) || $str===false) {
            return false;
        }

        $found = array();
        foreach ($array as $k => $v) {
            if (is_object($v)) {
                if ($v->$attribute === $str) {
                    $found[] = $k;
                }
            } else if (isset($v[$attribute])) {
                if (is_array($v[$attribute])) {
                    // Caso o Attributo seja um array entao procura normalmente,
                    // sem levar em conta o parametro
                    if ($subFound = self::arrayIsearch($str, $v[$attribute])) {
                        $found[] = $k;
                    }
                } else if (strtolower($v[$attribute]) == strtolower($str)) {
                    $found[] = $k;
                }
            } else if (is_array($v)) {
                if ($subFound = self::arraySearchByAttribute($str, $v, $attribute)) {
                    $found[] = $k;
                }
            }
        }

        if (empty($found)) {
            return false;
        }

        return $found;
    }
    
}
