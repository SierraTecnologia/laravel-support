<?php

namespace Support\Exceptions\HttpException;

use Support\Exceptions\HttpException;

class NotAuthorizedException extends HttpException
{
    /**
     * @var int
     */
    protected $errorCode = 401;

    /**
     * @var string
     */
    protected $statusMessage = 'Not Authorized';
}
