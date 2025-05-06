<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Exceptions\HttpNotAcceptableException;
use App\Exceptions\HttpUnauthorizedException;
use Exception;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Firebase\JWT\JWT;

use Firebase\JWT\Key;
use LogicException;
use UnexpectedValueException;

class AuthMiddleware implements MiddlewareInterface
{

    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        if (!$request->hasHeader('Accept')) {
            throw new HttpNotAcceptableException($request);
        }

        $aut = $request->getHeaderLine('Authorization');
        $jwt = str_replace("Bearer", "", $aut);

        try {
            $jwt_decode = (array)JWT::decode($jwt, new Key($key, 'HS256'));
        } catch (LogicException $e) {
            throw new HttpUnauthorizedException($request, $e->getMessage());
        } catch (UnexpectedValueException $e) {
            throw new HttpNotAcceptableException($request, $e->getMessage());
        }

        // You can inject user information into the request object
        $request->withAttribute('email', $jwt['email']);

        // Invoke the next middleware and get response
        $response = $handler->handle($request);
        // See attributes in the slim framework
        return $response;
    }
}
