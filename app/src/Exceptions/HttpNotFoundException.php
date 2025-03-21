<?php

namespace App\Exceptions;

use Slim\Exception\HttpSpecializedException;

class HttpNotFoundException  extends HttpSpecializedException
{
    protected $code = 404;
    protected $message = 'Not Found';
    protected string $title = '404 Not Found';
    protected string $description = 'The request was not found';
}
