<?php

declare(strict_types=1);

use App\Middleware\HelloMiddleware;
use App\Middleware\LoggingMiddleware;

use App\Middleware\ContentNegotiationMiddleware;
use App\Middleware\AuthMiddleware as AuthMiddleware;
use Psr\Http\Message\ServerRequestInterface;
use App\Helpers\LogHelper;
use App\Handlers\LoggingErrorHandler;
use Slim\Handlers\ErrorHandler;
use Monolog\Logger;

use Slim\App;

return function (App $app) {
    //TODO: Add your middleware here.



    $app->addRoutingMiddleware();

    $app->addBodyParsingMiddleware();
    //* Logging middleware
    $app->add(LoggingMiddleware::class);

    $app->add(ContentNegotiationMiddleware::class);



    // //* AuthMiddleware
    //     $app->add(\App\Middleware\AdminMiddleware::class);

    //    $app->add(AuthMiddleware::class);



    //!NOTE: the error handling middleware MUST be added last.
    $errorMiddleware = $app->addErrorMiddleware(true, true, true);
    $errorMiddleware->getDefaultErrorHandler()->forceContentType(APP_MEDIA_TYPE_JSON);

    // $errorMiddleware->setDefaultErrorHandler(
    //     new LoggingErrorHandler(
    //         $app->getCallableResolver(),
    //         $app->getResponseFactory()
    //     )
    // );
    //!NOTE: You can add override the default error handler with your custom error handler.
    //* For more details, refer to Slim framework's documentation.
    // Custom error handler for logging errors
    $customErrorHandler = function (
        ServerRequestInterface $request,
        Throwable $exception,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails
    ) use ($app) {

        // Log to error.log
        LogHelper::writeToErrorLog($exception, $request);

        // structure the error payload
        $payload = [
            'error' => true,
            'exception' => [
                'type' => get_class($exception),
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            ]
        ];


        $response = $app->getResponseFactory()->createResponse(
            $exception->getCode(),
            $exception->getMessage()
        );

        $response->getBody()->write(json_encode($payload, JSON_PRETTY_PRINT));

        return $response->withHeader('Content-Type', 'application/json');
    };

    $errorMiddleware->setDefaultErrorHandler($customErrorHandler);
};
