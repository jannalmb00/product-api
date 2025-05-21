<?php

declare(strict_types=1);

use App\Core\AppSettings;
use App\Core\PDOService;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Slim\Factory\AppFactory;
use Slim\App;
use Slim\Interfaces\RouteParserInterface;
use App\Middleware\AuthMiddleware;
use App\Middleware\AdminMiddleware;


$definitions = [
    AppSettings::class => function () {
        return new AppSettings(
            require_once __DIR__ . '/settings.php'
        );
    },
    App::class => function (ContainerInterface $container) {

        $app = AppFactory::createFromContainer($container);
        //$app->setBasePath('/slim-template');
        //echo APP_BASE_PATH;exit;
        $app->setBasePath('/' . APP_ROOT_DIR);
        $settings = $container->get(AppSettings::class)->get();
        // Register routes
        (require_once realpath(__DIR__ . '/../Routes/routes.php'))($app, $settings);

        // Register middleware
        (require_once realpath(__DIR__ . '/middleware.php'))($app, $settings);

        return $app;
    },


    PDOService::class => function (ContainerInterface $container): PDOService {
        $db_config = $container->get(AppSettings::class)->get('db');
        return new PDOService($db_config);
    },

    // HTTP factories
    ResponseFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(Psr17Factory::class);
    },
    ServerRequestFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(Psr17Factory::class);
    },
    StreamFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(Psr17Factory::class);
    },
    UriFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(Psr17Factory::class);
    },

    // // Adding the auth dependency injection via factory in container because it allows for flexible and organized way of retrieving the key under App Settings
    // AuthMiddleware::class => function (ContainerInterface $container) {
    //     $settings = $container->get(AppSettings::class);
    //     $jwtKey = $settings->get('jwt_key');
    //     return new AuthMiddleware($jwtKey);
    // },

    // // Adding admin middleware
    // AdminMiddleware::class => function () {
    //     return new AdminMiddleware();
    // },

    // The Slim RouterParser
    RouteParserInterface::class => function (ContainerInterface $container) {
        return $container->get(App::class)->getRouteCollector()->getRouteParser();
    },
];
return $definitions;
