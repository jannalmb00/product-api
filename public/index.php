<?php

declare(strict_types=1);


session_unset();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
session_destroy();



// This is the front controller of the Slim application.
(require_once realpath(__DIR__ . '/../app/config/bootstrap.php'))->run();
