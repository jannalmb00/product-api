<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Exceptions\HttpForbiddenException;

/**
 * Middleware that restrict unauthorized role to admin-only route
 *
 * Thia middleware checks the JWT if it contains the 'isAdmin = 1'
 */
class AdminMiddleware implements MiddlewareInterface
{


    /**
     * Process an incoming request and determine wheteher the user is an admin
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     * @throws \App\Exceptions\HttpForbiddenException
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        //retrieve JWT from request attributes
        $jwt = $request->getAttribute('jwt');

        //check if the user has admin privileges
        if (!isset($jwt['isAdmin']) || !$jwt['isAdmin']) {
            //deny access if user is not an admin
            throw new HttpForbiddenException($request, "Admin privileges required.");
        }
        // if user is admin, allow request to proceed
        return $handler->handle($request);
    }
}

//* For the routing, I used slimref for the route prioxy collector
