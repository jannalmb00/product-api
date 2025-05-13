<?php

namespace App\Helpers;

use Monolog\Logger;
use Psr\Log\LogLevel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Monolog\Handler\StreamHandler;
use App\Helpers\DateTimeHelper as Date;

class LogHelper
{


    public static function writeToAccessLog(Request $request, Response $response): void
    {

        //  echo "2 GOES HERE";

        // Writes to access.log
        //1. create logger
        $logger = new Logger('access');
        //   echo "3 GOES HERE";

        //2. push a log record handler
        $file_path = APP_LOGS_PATH . '/access.log';
        $logger->pushHandler(new StreamHandler($file_path, LogLevel::INFO));


        //3. write a log record to the logger
        // We can add here the HTTP method

        // extract email from body
        $body = $request->getParsedBody();
        $bodyArray = isset($body[0]) ? $body[0] : "";
        $email = $bodyArray["email"];

        $data = [
            'method' => $request->getMethod(),
            'status' => $response->getStatusCode(),
            'ip' => $request->getServerParams()['REMOTE_ADDR'] ?? '-',
            'uri' => (string)$request->getUri(),
            'user' => $email
        ];
        $logger->info("Access", $data);
    }

    public static function writeToErrorLog(\Throwable $e, Request $request): void
    {

        //echo "2 GOES HERE";

        // Writes to access.log
        //1. create logger
        $logger = new Logger('error');
        // echo "3 GOES HERE";

        //2. push a log record handler
        $file_path = APP_LOGS_PATH . '/error.log';
        $logger->pushHandler(new StreamHandler($file_path, LogLevel::ERROR));

        //3. write a log record to the logger
        $data = [
            //  'extra'        => $request->getQueryParams(),
            //  'exception'    => $e->getTrace(),
            'method'      => $request->getMethod(),
            'ip'          => $request->getServerParams()['REMOTE_ADDR'] ?? '-',
            'url'         => (string)$request->getUri(),
            // 'user_id' => $request->getAttribute('userId') ?? 'guest',
        ];
        // dd($e->getMessage());
        $logger->error($e->getMessage(), $data);
    }
}
