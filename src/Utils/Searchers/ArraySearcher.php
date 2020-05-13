<?php

declare(strict_types=1);

namespace Support\Utils\Searchers;

use Log;
use ArgumentCountError;
use Symfony\Component\Inflector\Inflector;
use Illuminate\Database\Eloquent\Relations\Relation;
use Support\Utils\Modificators\StringModificator;

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
    public static function arrayIsearch($str, $array){
        $found = array();
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                if ($subFound = self::arrayIsearch($str, $v)){
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
                if (strtolower($v) == strtolower($str)) {
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
