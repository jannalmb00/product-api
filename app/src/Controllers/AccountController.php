<?php

namespace App\Controllers;

use App\Core\AppSettings;
use App\Exceptions\HttpInvalidInputException;
use App\Models\AccountModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AccountController extends BaseController
{

    public function __construct(protected AppSettings $appSettings, AccountModel $accountModel) {}


    public function handleUserLogin(Request $request, Response $response): Response
    {
        // echo "QUACK!";

        // Code that generates a JWT token.

        $iat = time() + 60;
        $user_id = '2';
        // 1) Prepare the payload.
        $key = 'example_key';
        $payload = [
            'iss' => 'http://localhost/product-api',
            'aud' => 'http://localhost/product-api',
            'iat' => $iat,
            'email' => 'me@me.com',
            'user_id' => $user_id
            ///'nbf' => 1357000000
        ];

        $key = $this->appSettings->get("jwt_key");
        /**
         * IMPORTANT:
         * You must specify supported algorithms for your application. See
         * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
         * for a list of spec-compliant algorithms.
         */
        $jwt = JWT::encode($payload, $key, 'HS256');
        // $decoded = JWT::decode($jwt, new Key($key, 'HS256'));

        // print_r($jwt);
        $response_payload = [
            "stauts" => "success",
            "token" => $jwt,
        ];


        return $this->renderJson($response, $response_payload);
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
