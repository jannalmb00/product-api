<?php

namespace App\Handlers;

use Slim\Handlers\ErrorHandler;
use App\Helpers\LogHelper;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface;

class LoggingErrorHandler extends ErrorHandler
{
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
     * 
     */
    protected function respond(): ResponseInterface
    {
        $exception = $this->exception;

        // Exception payload
        $payload = [
            'error' => true,
            'exception' => [
                'type'    => get_class($exception),
                'message' => $exception->getMessage(),
                'file'    => $exception->getFile(),
                'line'    => $exception->getLine(),
            ],
        ];

        // Create response and write to json
        $response = $this->responseFactory->createResponse(500);
        $response->getBody()->write(json_encode($payload, JSON_UNESCAPED_SLASHES));

        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}
