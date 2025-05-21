<?php

namespace App\Controllers;

use App\Core\AppSettings;
use App\Exceptions\HttpInvalidInputException;
use App\Models\AccountModel;
use App\Services\AccountsService;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Controller that is for handling user registration and login
 */
class AccountController extends BaseController
{

    public function __construct(protected AppSettings $appSettings, private AccountModel $accountModel, private AccountsService $accountsService)
    {
        parent::__construct();
    }


    public function handleUserLogin(Request $request, Response $response): Response
    {

        // GET rquest body
        $login_info = $request->getParsedBody();

        if (empty($login_info) || !isset($login_info['email']) || !isset($login_info['password'])) {
            throw new HttpInvalidInputException(
                $request,
                "Invalid login information! Please input email and password!"
            );
        }

        try {
            // Auth user
            $result = $this->accountsService->authenticateUser(
                $login_info['email'],
                $login_info['password']
            );

            if ($result->isSuccess()) { // If successful, continue to retrieve the payload
                $user_info = $result->getData();
                $iat = time(); // Issued at
                $eat = $iat + 3600; // Expires at

                $payload = [
                    'iss' => 'http://localhost/product-api',
                    'aud' => 'http://localhost/product-api',
                    'iat' => $iat,
                    'exp' => $eat,
                    'user_id' => $user_info['user_id'],
                    'email' => $user_info['email'],
                    'role' => $user_info['role'],
                    ///'nbf' => 1357000000
                ];

                $key = $this->appSettings->get("jwt_key");
                $jwt = JWT::encode($payload, $key, 'HS256');


                // print_r($jwt);
                $response_payload = [
                    "status" => "success",
                    "code" => 200,
                    "message" => "Login successful",
                    "token" => $jwt,
                    "expires" => $eat,
                    "user" => [
                        "id" => $user_info->user_id,
                        "email" => $user_info->email,
                        "role" => $user_info->role,
                    ]
                ];

                return $this->renderJson($response, $response_payload);
            } else {

                $error_response_payload = [
                    "status" => "error",
                    "code" => 401,
                    "message" => $result->getMessage()
                ];

                return $this->renderJson($response, $error_response_payload, 401);
            }
        } catch (Exception $e) {

            $error_response_payload = [
                "status" => "error",
                "code" => 500,
                "message" => "Login errror. Please try again."
            ];

            return $this->renderJson($response, $error_response_payload, 500);
        }
    }

    public function handleUserRegistration(Request $request, Response $response): Response
    {

        $body = $request->getParsedBody();

        if (empty($body)) {
            throw new HttpInvalidInputException(
                $request,
                "Empty body"
            );
        }
        return $response;
    }
}
