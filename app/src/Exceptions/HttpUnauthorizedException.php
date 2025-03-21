<?php

namespace App\Exceptions;

use Slim\Exception\HttpSpecializedException;

class HttpUnauthorizedException  extends HttpSpecializedException
{
    protected $code = 401;
    protected $message = 'Unauthorized';
    protected string $title = '401 Unauthorized';
    protected string $description = 'Authentication is needed to access this resource';
}
