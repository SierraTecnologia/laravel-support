<?php

declare(strict_types=1);

namespace Muleta\Traits\Debugger;

use Muleta\Utils\Debugger\ErrorHelper;

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

trait HasErrors
{

    /**
     * Error
     */
    protected $errors = [];
    protected $isError = false;
    protected $warnings = [];
    protected $isWarning = false;
    protected $debugs = [];
    protected $isDebug = false;

    public function markWithError()
    {  
        return $this->isError = true;
    }

    public function hasError()
    { 
        return $this->isError;
    }

    /**
     *
     * @return void
     */
    public function getError()
    {
        return $this->errors;
    }
    public function getErrors()
    {
        return $this->getError();
    }

    public function getWarning()
    {
        return $this->warnings;
    }
    public function getWarnings()
    {
        return $this->getWarning();
    }

    public function getDebug()
    {
        return $this->debugs;
    }
    public function getDebugs()
    {
        return $this->getDebug();
    }

    /**
     * @todo Dependendo Criar Gerenciador de Error
        $this->setError(
            \Support\Components\Errors\TableNotExistError::make(
                $className
            )
        );
     */


    /**
     * Update the table.
     *
     * @return void
     */
    public function setErrors($errors, $reference = [], $debugData = [])
    {  
        if (is_array($errors)) {
            // if (is_array($error) && count($error) == 1) {
            foreach ($errors as $error) {
                $this->setError($error, $reference, $debugData);
            }
            return true;
        }
        return $this->setError($errors, $reference, $debugData);
    }

    /**
     * Update the table.
     *
     * @return void
     */
    public function setError($error, $reference = [], $debugData = [])
    { 
        if (ErrorHelper::isToIgnore($error)) {
            //     dd('Istoignore',
            // $error);
            return false;
        }
        $reference['locateClassFromError'] = get_class($this);

        $this->errors[] = ErrorHelper::registerAndReturnMessage($error, $reference, 'error');
        $this->isError = true;

        if (ErrorHelper::isToDebug($reference)) {
            dd(
                'IsToDebug',
                $error,
                $reference,
                $debugData
            );
        }
        
        return true;
    }



    /**
     * Update the table.
     *
     * @return void
     */
    public function setWarnings($warnings, $reference = [], $debugData = [])
    {  
        if (is_array($warnings)) {
            // if (is_array($warning) && count($warning) == 1) {
            foreach ($warnings as $warning) {
                $this->setWarning($warning, $reference);
            }
            return true;
        }
        return $this->setWarning($warnings, $reference);
    }

    /**
     * Update the table.
     *
     * @return void
     */
    public function setWarning($warning, $reference = [], $debugData = [])
    { 
        if (ErrorHelper::isToIgnore($warning)) {
            return false;
        }
        $reference['locateClassFromWarning'] = get_class($this);

        $this->warnings[] = ErrorHelper::registerAndReturnMessage($warning, $reference, 'warning');
        $this->isWarning = true;

        if (ErrorHelper::isToDebug($reference)) {
            dd(
                'IsToDebug Warning',
                $warning,
                $reference,
                $debugData
            );
        }
        
        return true;
    }


    /**
     * Update the table.
     *
     * @return void
     */
    public function setDebugs($debugs, $reference = [], $debugData = [])
    {  
        if (is_array($debugs)) {
            // if (is_array($debug) && count($debug) == 1) {
            foreach ($debugs as $debug) {
                $this->setDebug($debug, $reference);
            }
            return true;
        }
        return $this->setDebug($debugs, $reference);
    }

    /**
     * Update the table.
     *
     * @return void
     */
    public function setDebug($debug, $reference = [], $debugData = [])
    { 
        if (ErrorHelper::isToIgnore($debug)) {
            return false;
        }
        $reference['locateClassFromDebug'] = get_class($this);

        $this->debugs[] = ErrorHelper::registerAndReturnMessage($debug, $reference, 'debug');
        $this->isDebug = true;

        if (ErrorHelper::isToDebug($reference)) {
            dd(
                'IsToDebug Debug',
                $debug,
                $reference,
                $debugData
            );
        }
        
        return true;
    }

    /**
     * Merge Errors
     *
     * @return void
     */
    public function mergeErrors($errors)
    {  
        $this->errors = \array_merge(
            $this->errors, $errors
        );
    }

    /**
     * Run a function or getError
     *
     * @return void
     */
    public function forceExecute($function, $param = false, $data = [])
    {  
        try {
            $function();
        } catch(BindingResolutionException $e) {
            if (!$param) {
                $this->setErrors(
                    $e
                );
                return true;
            }
            // Erro Leve
            $this->setErrors(
                $e
            );
            
        } catch(SchemaException|DBALException $e) {
            if (!$param) {
                $this->setErrors(
                    $e
                );
                return true;
            }
            $this->setError(
                \Support\Components\Errors\TableNotExistError::make(
                    $param,
                    $data
                )
            );
        } catch(LogicException|ErrorException|RuntimeException|OutOfBoundsException|TypeError|ValidationException|FatalThrowableError|FatalErrorException|Exception|Throwable  $e) {
            if (!$param) {
                $this->setErrors(
                    $e
                );
                return true;
            }
            $this->setErrors(
                $e
            );
        } 
    }
}
