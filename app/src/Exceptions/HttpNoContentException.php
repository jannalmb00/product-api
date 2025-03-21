<?php

namespace App\Exceptions;

use Slim\Exception\HttpSpecializedException;

class HttpNoContentException  extends HttpSpecializedException
{
    protected $code = 204;
    protected $message = 'No Content';
    protected string $title = '204 No Content';
    protected string $description = 'Request successful. No data that matches the filter.';
}
