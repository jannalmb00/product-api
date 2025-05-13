<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Exceptions\HttpForbiddenException;

class AdminMiddleware implements MiddlewareInterface
{

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        $jwt = $request->getAttribute('jwt');
        //dd($jwt);
        if (!isset($jwt['isAdmin']) || !$jwt['isAdmin']) {
            throw new HttpForbiddenException($request, "Admin privileges required.");
        }

        return $handler->handle($request);
    }
}
