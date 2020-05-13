<?php

namespace Support\Exceptions\HttpException;

use Support\Exceptions\HttpException;

class NotFoundException extends HttpException
{
    /**
     * @var int
     */
    protected $errorCode = 404;

    /**
     * @var string
     */
    protected $statusMessage = 'Not Found';
}
