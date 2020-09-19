<?php

namespace Support\Exceptions\Coder;

/**
 * Used when validation fails. Contains the invalid model for easy analysis.
 * Class InvalidModelException
 *
 * @package Support\Exceptions\Coder
 */
class EloquentTableNotExistException extends EloquentHasErrorException
{
    
    /**
     * @var string
     */
    public $tableName;

    /**
     * @param string  $className
     * @param string  $tableName
     * @param integer $code
     */
    public function __construct(string $className, $tableName, $code = 0)
    {
        $this->tableName = $tableName;

        $message = 'Table '.$this->tableName.' not exist in database';

        parent::__construct($className, $message, $code);
    }
}