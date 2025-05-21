<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Exceptions\HttpNotAcceptableException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;

/**
 * Content Negotiation Middleware ensures the client accepts JSON responses and automatically decodes incoming JSON request bodies into associative array
 *
 *
 * An HTTP middleware component participates in processing an HTTP message:
 * by acting on the request, generating the response, or forwarding the
 * request to a subsequent middleware and possibly acting on its response.
 */
class ContentNegotiationMiddleware implements MiddlewareInterface
{
    /**
     * Process an incoming server request.
     *
     * Processes an incoming server request in order to produce a response.
     * If unable to produce the response itself, it may delegate to the provided
     * request handler to do so.
     *
     */
    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        // Optional: Handle the incoming request
        // ...
        //1) Validate 'Accept' header
        if (!$request->hasHeader('Accept')) {
            throw new HttpNotAcceptableException($request);
        }

        $contentType = $request->getHeaderLine('Accept');
        //Only allow  'application/json' or wildcard '*/*'
        if (!str_contains($contentType, 'application/json') && !str_contains($contentType, '*/*')) {
            throw new HttpNotAcceptableException($request);
        }


        // 2) Read raw body using PSR-7 stream and decode
        $bodyStream = $request->getBody();

        // Rewind before reading
        if ($bodyStream->isSeekable()) {
            $bodyStream->rewind();
        }

        $raw = $bodyStream->getContents();
        $decoded = json_decode($raw, true);
        if (JSON_ERROR_NONE === json_last_error()) {
            $request = $request->withParsedBody($decoded);
        }

        // Rewind again for downstream middleware and controllers
        if ($bodyStream->isSeekable()) {
            $bodyStream->rewind();
        }
        // //! DO NOT remove or change the following statements.

        // Invoke the next middleware and get response
        $response = $handler->handle($request);

        // Optional: Handle the outgoing response
        // ...

        return $response;
    }
}
