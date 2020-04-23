<?php

declare(strict_types=1);

namespace Support\ClassesHelpers\Development;

trait HasErrors
{

    /**
     * Error
     */
    protected $error = [];
    protected $isError = false;

    /**
     * Update the table.
     *
     * @return void
     */
    public function setErrors($errors)
    {  
        if (is_array($errors)) {
            // if (is_array($error) && count($error) == 1) {
            foreach ($errors as $error) {
                $this->setError($error);
            }
            return true;
        }
        return $this->setError($errors);
    }

    /**
     * Update the table.
     *
     * @return void
     */
    public function setError($error)
    {  
        if (ErrorHelper::isToIgnore($error)) {
            return false;
        }

        $this->error[] = ErrorHelper::registerAndReturnMessage($error);
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

}
