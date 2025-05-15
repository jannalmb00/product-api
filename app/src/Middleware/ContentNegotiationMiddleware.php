<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Exceptions\HttpNotAcceptableException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;

/**
 * Participant in processing a server request and response.
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
     */
    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        // Optional: Handle the incoming request
        // ...
        if (!$request->hasHeader('Accept')) {
            throw new HttpNotAcceptableException($request);
        }

        $contentType = $request->getHeaderLine('Accept');
        if (!str_contains($contentType, 'application/json') && !str_contains($contentType, '*/*')) {
            throw new HttpNotAcceptableException($request);
        }

        // $contents = json_decode(file_get_contents('php://input'), true);
        // if (json_last_error() === JSON_ERROR_NONE) {
        //     $request = $request->withParsedBody($contents);
        // }
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
