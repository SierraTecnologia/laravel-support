<?php

declare(strict_types=1);

namespace Support\Traits\Debugger;

use Support\Utils\Debugger\ErrorHelper;

trait HasErrors
{

    /**
     * Error
     */
    protected $error = [];
    protected $isError = false;
    protected $warning = [];
    protected $isWarning = false;

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
        return $this->error;
    }
    public function getErrors()
    {
        return $this->getError();
    }

    public function getWarning()
    {
        return $this->warning;
    }
    public function getWarnings()
    {
        return $this->getWarning();
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
            return false;
        }
        $reference['locateClassFromError'] = get_class($this);

        $this->error[] = ErrorHelper::registerAndReturnMessage($error, $reference, 'error');
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

        $this->warning[] = ErrorHelper::registerAndReturnMessage($warning, $reference, 'warning');
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
    public function mergeErrors($errors)
    {  
        $this->error = \array_merge(
            $this->error, $errors
        );
    }
}
