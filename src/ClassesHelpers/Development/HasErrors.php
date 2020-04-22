<?php

declare(strict_types=1);

namespace Support\ClassesHelpers\Development;

use Log;
use ArgumentCountError;

trait HasErrors
{

    /**
     * Error
     */
    protected $error = [];
    protected $isError = false;

    protected $ignoreErrosWithStrings = [
        'deletePreservingMedia() must be of the type bool, null returned',
        'No repository injected in project',
        'Unable to determine if repository is empty',
    ];

    protected $ignoreExceptionsErrors = [
        ArgumentCountError::class,
    ];



    /**
     * Update the table.
     *
     * @return void
     */
    public function setError($error)
    {  
        if ($this->isToIgnore($error)) {
            return false;
        }

        if (is_array($error) && count($error) == 1) {
            $error = $error[0];
        }
        if (is_object($error)) {
            $e = $error;
            $error = $error->getMessage();
        }

        // dd($e);
        Log::error($error);
        $this->error[] = $error;
        $this->isError = true;
        
        return true;
    }

    /**
     *
     * @return void
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     *
     * @return void
     */
    protected function isToIgnore($error)
    {
        if (empty($error)) {
            return true;
        }

        // Verifica Mensagem de Erro
        foreach ($this->ignoreErrosWithStrings as $str) {
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
            foreach ($this->ignoreExceptionsErrors as $className) {
                if (is_a($error, $className) !== false) {
                    return true;
                }
            }
        }
        return false;
    }
}
