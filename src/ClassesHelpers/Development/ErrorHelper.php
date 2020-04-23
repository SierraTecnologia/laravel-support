<?php

declare(strict_types=1);

namespace Support\ClassesHelpers\Development;

use Log;
use ArgumentCountError;

class ErrorHelper
{


    public static $ignoreErrosWithStrings = [
        'deletePreservingMedia() must be of the type bool, null returned',
        'No repository injected in project',
        'Unable to determine if repository is empty',
    ];

    public static $ignoreExceptionsErrors = [
        ArgumentCountError::class,
    ];
    /**
     *
     * @return void
     */
    public static function registerAndReturnMessage($error)
    {
        if (is_object($error)) {
            $e = $error;
            $error = $error->getMessage();
        }
        // @todo Gravar no Banco para tratar depois
        // dd($e);
        Log::error($error);
        return $error;
    }

    /**
     *
     * @return void
     */
    public static function isToIgnore($error)
    {
        if (empty($error)) {
            return true;
        }

        // Verifica Mensagem de Erro
        foreach (static::$ignoreErrosWithStrings as $str) {
            $errorMessage = $error;
            if (is_object($error)) {
                $errorMessage = $error->getMessage();
            }
            if (strpos($errorMessage, $str) !== false) {
                return true;
            }
        }
        // Verifica Exception
        if (is_object($error)) {
            foreach (static::$ignoreExceptionsErrors as $className) {
                if (is_a($error, $className) !== false) {
                    return true;
                }
            }
        }
        return false;
    }
}
