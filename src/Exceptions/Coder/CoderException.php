<?php

namespace Support\Exceptions\Coder;

use Support\Exceptions\Exception;

/**
 * Exception da Manipulação do Código
 *
 * @package Support\Exceptions\Coder
 */
class CoderException extends Exception
{
    /**
     * @var int
     */
    protected $errorCode = 2801;

    // /**
    //  * @var string
    //  */
    // protected $statusMessage = 'Eloquent has Error';

    // /**
    //  * @return int
    //  */
    // public function getErrorCode()
    // {
    //     return $this->errorCode;
    // }

    // /**
    //  * @return string
    //  */
    // public function getStatusMessage()
    // {
    //     return $this->statusMessage;
    // }

    // /**
    //  * @return string
    //  */
    // public function getHttpHeader()
    // {
    //     return 'HTTP/1.1 ' . $this->errorCode . ' ' . $this->statusMessage;
    // }
}