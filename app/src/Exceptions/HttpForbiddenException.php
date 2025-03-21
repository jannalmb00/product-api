<?php

namespace App\Exceptions;

use Slim\Exception\HttpSpecializedException;

class HttpForbiddenException  extends HttpSpecializedException
{
    protected $code = 403;
    protected $message = 'Forbidden';
    protected string $title = '403 Forbidden';
    protected string $description = 'User is not authorized to access this resource';
}
