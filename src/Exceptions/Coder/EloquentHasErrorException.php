<?php

namespace Support\Exceptions\Coder;

/**
 * Used when validation fails. Contains the invalid model for easy analysis.
 * Class InvalidModelException
 *
 * @package Support\Exceptions\Coder
 */
class EloquentHasErrorException extends CoderException
{
    
    /**
     * @var string
     */
    public $className;

    /**
     * @param string  $className
     * @param string  $message
     * @param integer $code
     */
    public function __construct(string $className, $message = null, $code = 0)
    {
        $this->className = $className;

        parent::__construct($message, $code);
    }
}