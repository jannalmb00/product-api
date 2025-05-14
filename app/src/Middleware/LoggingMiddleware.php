<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\AppSettings;
use App\Models\AccessModel;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use App\Helpers\LogHelper;

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
        $response = $handler->handle($request);
        // echo "DB name " . $this->app_settings->get("db")["database"];
        // echo "1 GOES HERE ";

        // TODO: make LogHelper class
        //* 1) Write to access.log using the LogHelper class
         LogHelper::writeToAccessLog($request, $response);
        //  echo "5 GOES HERE";


        //* 2) Insert log records into the ws_user DB table --> Log Helper needs to be implemented and tested before this
        // Note: See aa_tables.zip on LEA. contains db schema to import to phpmyadmin
        // We need an instance of AccessModel -> this is done by adding the access model to cosntructor --> done
        //*
        // Inserts to db
        // get the response body and its content
        $responseBody = $response->getBody();
        if ($responseBody->isSeekable()) {
            $responseBody->rewind();
        }
        $contents = (string) $responseBody->getContents();

        // Decode JSON to array
        $data = json_decode($contents, true) ?: [];

        //Prepare data to pass to log to db
        $userId = isset($data['user_id']) ? $data['user_id'] : null;
        // dd($userId);
        $email = $data['user_email'] ?? $data['email'] ?? 'guest';
        //dd($email);

        $user_action = $data['isAdmin'] ? 'admin' : 'guest';

        $logData = [
            'user_id' => $userId,
            'email' => $email,
            'user_action' => $user_action,
        ];

        // Pass to access model to insert to db
        $this->access_model->insertLog($logData);

        //! DO NOT remove or change the following statements.
        // Invoke the next middleware and get response
        $response = $handler->handle($request);
        // Optional: Handle the outgoing response
        // ...

        return $response;
    }
}
