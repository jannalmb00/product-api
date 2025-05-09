<?php

declare(strict_types=1);

use App\Middleware\HelloMiddleware;
use App\Middleware\LoggingMiddleware;

use App\Middleware\ContentNegotiationMiddleware;
use App\Middleware\AuthMiddleware;
use Slim\App;

return function (App $app) {
    //TODO: Add your middleware here.
    $app->add(ContentNegotiationMiddleware::class);
    $app->addBodyParsingMiddleware();
    $app->addRoutingMiddleware();

    //* AuthMiddleware
    $app->add(AuthMiddleware::class);

    //* Logging middleware
    //$app->add(LoggingMiddleware::class);


    //!NOTE: the error handling middleware MUST be added last.
    $errorMiddleware = $app->addErrorMiddleware(true, true, true);
    $errorMiddleware->getDefaultErrorHandler()->forceContentType(APP_MEDIA_TYPE_JSON);
    //!NOTE: You can add override the default error handler with your custom error handler.
    //* For more details, refer to Slim framework's documentation.
};
