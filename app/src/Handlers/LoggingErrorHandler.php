<?php

namespace App\Handlers;

use Slim\Handlers\ErrorHandler;
use App\Helpers\LogHelper;
use Psr\Http\Message\ResponseInterface;

/**
 * Custom error handler that logs exceptions and returns structured JSON error responses.
 */
class LoggingErrorHandler extends ErrorHandler
{

    /**
     * Logs the error using a custom LogHelper class.
     * @param string $error The error message.
     * @return void
     */
    protected function logError(string $error): void
    {
        // Insert custom error logging function.
        parent::logError($error);

        // Log the error
        LogHelper::writeToErrorLog(
            $this->exception,
            $this->request
        );
    }


    /**
     * Generates the JSON response returned when an exception occurs.
     * @return ResponseInterface
     */
    protected function respond(): ResponseInterface
    {
        $exception = $this->exception;

        // Exception payload
        $payload = [
            'error' => false,
            'exception' => [
                'type'    => get_class($exception),
                'message' => $exception->getMessage(),
                'file'    => $exception->getFile(),
                'line'    => $exception->getLine(),
            ],
        ];

        // Create response and write to json
        $response = $this->responseFactory->createResponse(
            $exception->getCode(),
            $exception->getMessage()
        );
        $response->getBody()->write(json_encode($payload, JSON_UNESCAPED_SLASHES));

        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}
