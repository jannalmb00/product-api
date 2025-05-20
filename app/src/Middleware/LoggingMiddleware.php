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

        //! DO NOT remove or change the following statements.
        // Invoke the next middleware and get response
        // Optional: Handle the outgoing response
        // ...
        $response = $handler->handle($request);
        // echo "DB name " . $this->app_settings->get("db")["database"];
        //echo "1 GOES HERE ";
        // TODO: make LogHelper class
        //* 1) Write to access.log using the LogHelper class
        LogHelper::writeToAccessLog($request, $response);
        //echo "5 GOES HERE";


        //* 2) Insert log records into the ws_user DB table --> Log Helper needs to be implemented and tested before this
        // Note: See aa_tables.zip on LEA. contains db schema to import to phpmyadmin
        // We need an instance of AccessModel -> this is done by adding the access model to cosntructor --> done
        //*
        // Inserts to db
        // get the response body and its content
        //! Register
        $body = $request->getParsedBody();


        // //        dd($responseBody);
        if (is_array($body)) {
            $bodyArray = isset($body[0]) ? $body[0] : "";
            $email = $bodyArray["email"];
        }

        // $data = [
        //     'method' => $request->getMethod(),
        //     'ip' => $request->getServerParams()['REMOTE_ADDR'] ?? '-',
        //     'resource' => (string)$request->getUri(),
        //     'parameters' => $request->getQueryParams(),
        //     'user' => $email
        // ];
        // // Decode JSON to array
        // $data = json_decode($contents, true) ?: [];
        // dd($data);
        // // //Prepare data to pass to log to db
        // $userId = isset($data['user_id']) ? $data['user_id'] : null;
        // // dd($userId);
        // $email = $data['user_email'] ?? $data['email'] ?? 'guest';
        //  $user_action = $data['isAdmin'] ? 'admin' : 'guest';

        //dd($data);
        //! Logs when registering

        $user_action = $request->getMethod() . ' ' . (string) $request->getUri()->getPath();

        $responseBody = $response->getBody();
        // Rewind the stream if needed
        if ($response->getBody()->isSeekable()) {
            $response->getBody()->rewind();
        }
        $json = json_decode((string)$responseBody, true);
        if (is_array($json)) {
            $user_id = $json['user_id'] ?? "";
        }



        $logData = [
            'email' => $email,
            'user_action' => $user_action,
            'user_id' => $user_id
        ];

        // Pass to access model to insert to db
        $this->access_model->insertLog($logData);



        return $response;
    }
}
