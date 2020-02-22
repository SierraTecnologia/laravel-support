<?php

declare(strict_types=1);

namespace Support\ClassesHelpers\Development;

use Log;

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
    public function setError($error)
    {
        if (!empty($error)) {
            if (is_array($error) && count($error) == 1) {
                $error = $error[0];
            }


            Log::error($error);
            $this->error[] = $error;
            $this->isError = true;
        }
    }

    /**
     * Update the table.
     *
     * @return void
     */
    public function getError()
    {
        return $this->error;
    }
}
