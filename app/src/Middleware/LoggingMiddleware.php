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
 *
 *
 * Middleware that logs request and response information
 * used for tracking APIusage, debugging, and analytics
 *
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

        //! DO NOT remove or change the following statements.
        // Invoke the next middleware and get response
        // Optional: Handle the outgoing response

        // 1.) Forward the request and get the response
        $response = $handler->handle($request);

        // TODO: make LogHelper class
        //2) Write to access.log using the LogHelper class
        LogHelper::writeToAccessLog($request, $response);


        //* 2) Insert log records into the ws_user DB table --> Log Helper needs to be implemented and tested before this
        // We need an instance of AccessModel -> this is done by adding the access model to cosntructor --> done

        // Inserts to db. get the response body and its content
        //! Register
        $body = $request->getParsedBody();

        // Prepare details to add to db
        if (is_array($body)) {
            $bodyArray = isset($body[0]) ? $body[0] : "";
            // $email = $bodyArray["email"];
        }

        //! Logs when registering
        //3. Construct user action sring
        $user_action = $request->getMethod() . ' ' . (string) $request->getUri()->getPath();

        //4. Read the response body
        $responseBody = $response->getBody();

        // Rewind the stream if needed
        if ($response->getBody()->isSeekable()) {
            $response->getBody()->rewind();
        }

        $json = json_decode((string)$responseBody, true);
        if (is_array($json)) {
            $user_id = $json['user_id'] ?? "";
        }

        // to add user to the log
        $user_id = $request->getAttribute('jwt')['user_id'] ?? '';

        //5. Prepare log data for database
        $logData = [
            'user_action' => $user_action,
            'email' => $user_id,
            //'ip_address' => $request->getServerParams()['REMOTE_ADDR'] ?? 'unknown',

        ];

        // Pass to access model to insert to db
        $this->access_model->insertLog($logData);


        //8 Return the original response unchanged
        return $response;
    }
}
