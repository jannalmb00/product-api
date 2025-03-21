<?php

namespace App\Exceptions;

use Slim\Exception\HttpSpecializedException;

class HttpNotAcceptableException  extends HttpSpecializedException
{
    protected $code = 415;
    protected $message = 'Not Acceptable';
    protected string $title = '415 Not Acceptable';
    protected string $description = 'Type of resource representation is not supported';
}
