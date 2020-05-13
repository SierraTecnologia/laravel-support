<?php

namespace Support\Exceptions\HttpException;

use Support\Exceptions\HttpException;

class BadRequestException extends HttpException
{
    /**
     * @var int
     */
    protected $errorCode = 400;

    /**
     * @var string
     */
    protected $statusMessage = 'Bad Request';
}
