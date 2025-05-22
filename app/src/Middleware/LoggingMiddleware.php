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
use Throwable;

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
        $statusCode = $response->getStatusCode();

        $message = "Status Code: " . $statusCode;
        if ($response->getStatusCode() < 400) {
            LogHelper::writeToAccessLog($request, $response);
        } else {
            LogHelper::writeToErrorLog(new \Exception($message), $request);
        }

        // LogHelper::writeToAccessLog($request, $response);

        // Inserts to db. get the response body and its content
        //* Register
        $body = $request->getParsedBody();

        //3.Extracts email from body / session
        //* When registering: checks if email is in the body, to add it to the log
        $email = "";
        if (isset($body[0]["email"])) {
            // For REGISTER & LOGIN
            $email = $body[0]["email"];
        } else if (isset($_SESSION['user']['email'])) {
            // adds email using session: used for GET
            $email = $_SESSION['user']['email'];
        }

        //4. Construct user action sring
        $user_action = $request->getMethod() . ' ' . (string) $request->getUri()->getPath();

        // 5. Gets userid and email from session
        $user_id = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : "";

        //6. Prepare log data for database - no ip for the db
        $logData = [
            'user_action' => $user_action,
            'user_id' => $user_id,
            'email' => $email,
        ];

        // 7. Pass to access model to insert to db
        $this->access_model->insertLog($logData);

        //8 Return the original response unchanged
        return $response;
    }
}
