<?php

namespace Support\Exceptions\HttpException;

use Support\Exceptions\HttpException;

class ForbiddenException extends HttpException
{
    /**
     * @var int
     */
    protected $errorCode = 403;

    /**
     * @var string
     */
    protected $statusMessage = 'Forbidden';
}
