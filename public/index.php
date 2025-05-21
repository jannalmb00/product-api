<?php

declare(strict_types=1);




if (session_status() === PHP_SESSION_NONE) {
    session_start();
}



// This is the front controller of the Slim application.
(require_once realpath(__DIR__ . '/../app/config/bootstrap.php'))->run();
