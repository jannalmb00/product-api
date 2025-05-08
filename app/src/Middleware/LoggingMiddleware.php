<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\AppSettings;
use App\Models\AccessModel;
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
class LoggingMiddleware implements MiddlewareInterface
{

    public function __construct(private AppSettings $app_settings, private AccessModel $access_model) {}


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

        $this->access_model->insertLog("Oi, QUCACKK");

        echo "DB name " . $this->app_settings->get("db")["database"];


        // TODO: make LogHelper class
        //* 1) Write to access.log using the LogHelper class
        //* 2) Insert log records into the ws_user DB table --> Log Helper needs to be implemented and tested before this
        // Note: See aa_tables.zip on LEA. contains db schema to import to phpmyadmin
        // We need an instance of AccessModel -> this is done by adding the access model to cosntructor
        //*


        //! DO NOT remove or change the following statements.
        // Invoke the next middleware and get response
        $response = $handler->handle($request);

        // Optional: Handle the outgoing response
        // ...

        return $response;
    }

   
}
