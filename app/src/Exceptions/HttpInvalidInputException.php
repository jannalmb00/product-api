<?php

namespace App\Exceptions;

use Slim\Exception\HttpSpecializedException;


class HttpInvalidInputException  extends HttpSpecializedException
{
    protected $code = 400;
    protected $message = 'Bad Request';
    protected string $title = '400 Bad Request';
    protected string $description = 'Invalid input was detected.';
}
