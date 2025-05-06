<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Exceptions\HttpNotAcceptableException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;

class TokenMiddleware implements MiddlewareInterface
{

    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        if (!$request->hasHeader('Accept')) {
            throw new HttpNotAcceptableException($request);
        }


        if (!$request->getHeaderLine('Authorization')) {
            // dd request header line
            dd($request);
        }

        $contents = json_decode(file_get_contents('php://input'), true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $request = $request->withParsedBody($contents);
        }

        // Invoke the next middleware and get response
        $response = $handler->handle($request);

        return $response;
    }
}
