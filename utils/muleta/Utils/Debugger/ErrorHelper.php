<?php

declare(strict_types=1);

namespace Muleta\Utils\Debugger;

use Log;
use ArgumentCountError;
use Support\Components\Errors\CodeError;

class ErrorHelper
{
    // @todo Add na configuracao
    protected static $showTrace = false;


    public static $ignoreErrosWithStrings = [
        'deletePreservingMedia() must be of the type bool, null returned',
        'No repository injected in project',
        'Unable to determine if repository is empty',
    ];

    public static $ignoreExceptionsErrors = [
        ArgumentCountError::class,
    ];

    /**
     * Debugs
     */
    public static $debugModels = [
        \Telefonica\Models\Actors\Person::class,
    ];


    /**
     * @return string
     */
    public static function registerError(string $error, $type = 'error'): string
    {
        if ($type === 'error') {
            Log::channel('sitec-support')->error($error);
        } else if ($type === 'warning') {
            // @todo desfazer comentario que botei pra nao sujar o log
            // Log::channel('sitec-support')->warning($error);
        } else if ($type === 'info') {
            Log::channel('sitec-support')->info($error);
        } else {
            Log::channel('sitec-support')->debug($error);
        }
        return $error;
    }
    public static function registerAndReturnMessage($error, $reference = false, $type = 'error'): string
    {
        return self::registerError(self::tratarMensagem($error, $reference), $type);
    }
    public static function tratarMensagem($error, $reference = false): string
    {
        if (is_object($error) && is_a($error, CodeError::class)) {
            return $error->getMessage();
        }
        
        if (is_object($error)) {
            $e = $error;
            // $error = $e->getMessage();
            $error = $e->getMessage().' | File: '.
            $e->getFile().' | Line: '.
            $e->getLine();

            if (self::$showTrace) {
                $error .= '| Trace: '.$e->getTraceAsString();
            }
        }

        if ($reference) {
            $error .= '| Reference: '.print_r($reference, true);
        }
        return $error;
    }

    /**
     * @return bool
     */
    public static function isToIgnore($error): bool
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
    /**
     * @return bool
     */
    public static function isToDebug($reference = false): bool
    {
        if (!$reference || empty($reference)) {
            return false;
        }

        $referencesIndexToSearch = [
            'model',
            'locateClassFromError'
        ];
        foreach ($referencesIndexToSearch as $indexToSearch) {
            if (isset($reference[$indexToSearch])) {
                foreach (self::$debugModels as $debugModel) {
                    if ($reference[$indexToSearch] == $debugModel) {
                        return true;
                    }
                }
            }
        }
        
        return false;
    }
}
