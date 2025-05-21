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
        // Writes to access.log
        //1. create logger
        $logger = new Logger('access');

        //2. push a log record handler
        $file_path = APP_LOGS_PATH . '/access.log';
        $logger->pushHandler(new StreamHandler($file_path, LogLevel::INFO));

        //3. write a log record to the logger

        // extract email from body
        $body = $request->getParsedBody();

        $status = $response->getStatusCode();

        $email = "";
        if (isset($body[0]["email"])) {
            // For REGISTER & LOGIN
            $email = $body[0]["email"];
        } else if (isset($_SESSION['user']['email'])) {
            // adds email using session: used for GET
            $email = $_SESSION['user']['email'];
        }

        //! Logs when registering
        $data = [
            'method' => $request->getMethod(),
            'resource' => (string)$request->getUri()->getPath(),
            'ip' => $request->getServerParams()['REMOTE_ADDR'] ?? '-',
            'parameters' => $request->getQueryParams(),
            'user' => $email,
            'status' => $status
        ];
        $logger->info("Access", $data);
    }

    public static function writeToErrorLog(\Throwable $e, Request $request): void
    {

        // Writes to error.log
        //1. create logger
        $logger = new Logger('error');

        //2. push a log record handler
        $file_path = APP_LOGS_PATH . '/error.log';
        $logger->pushHandler(new StreamHandler($file_path, LogLevel::ERROR));

        // extract email from body
        $body = $request->getParsedBody();

        $email = "";
        if (isset($body[0]["email"])) {
            // For REGISTER & LOGIN
            $email = $body[0]["email"];
        } else if (isset($_SESSION['user']['email'])) {
            // adds email using session: used for GET
            $email = $_SESSION['user']['email'];
        }

        //3. write a log record to the logger
        $data = [
            'exception'    => $e->getMessage(),
            'method'      => $request->getMethod(),
            'ip'          => $request->getServerParams()['REMOTE_ADDR'] ?? '-',
            'url'         => (string)$request->getUri(),
            'parameters' => $request->getQueryParams(),
            'user' => $email
        ];
        $logger->error($e->getMessage(), $data);
    }
}
