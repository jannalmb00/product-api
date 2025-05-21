<?php

declare(strict_types=1);

use App\Middleware\HelloMiddleware;
use App\Middleware\LoggingMiddleware;

use App\Middleware\ContentNegotiationMiddleware;
use App\Middleware\AuthMiddleware as AuthMiddleware;

use App\Handlers\LoggingErrorHandler;

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

    //!NOTE: You can add override the default error handler with your custom error handler.
    //* For more details, refer to Slim framework's documentation.
    // Custom error handler for logging errors
    // $errorMiddleware->setDefaultErrorHandler(
    //     new LoggingErrorHandler(
    //         $app->getCallableResolver(),
    //         $app->getResponseFactory()
    //     )
    // );
};
