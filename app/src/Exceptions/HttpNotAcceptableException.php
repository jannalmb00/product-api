<?php

namespace App\Exceptions;

use Slim\Exception\HttpSpecializedException;

class HttpNotAcceptableException  extends HttpSpecializedException
{
    protected $code = 406;
    protected $message = 'Not Acceptable';
    protected string $title = '406 Not Acceptable';
    protected string $description = 'Type of resource representation is not supported';
}
