<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use App\Models\UserModel;
use App\Services\UserService;
use App\Core\AppSettings;
use Exception;
use Firebase\JWT\JWT;


/**
 * Controller that is for handling the user creation and user login
 */
class UserController extends BaseController
{

    public function __construct(protected AppSettings $appSettings, private UserModel $user_model, private UserService $user_service) // replace the reference to a service, and the service will have a reference to the model
    {
        //To initialize the validator
        parent::__construct();
    }


    /**
     * POST: Handle the creation of users
     * ROUTE: POST /Register
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return Response
     */
    public function handleCreateRegister(Request $request, Response $response): Response
    {

        //TODO: Handle case where the case where the body could be empty
        $users_data = $request->getParsedBody();

        if (empty($users_data)) {
            throw new HttpBadRequestException($request, "Data passed is empty");
        }

        if (!is_array($users_data) || count($users_data) !== 1) {
            throw new HttpBadRequestException($request, "Invalid data format, expected an array with a single user object");
        }

        $user_data = $users_data[0];

        $result = $this->user_service->createUser($user_data);

        //* Dont forget to identify the outcome of the operations: success vs failure
        if ($result->isSuccess()) {
            // echo "SUCCESS";
            // Operation success
            $payload = [
                'status' => 'success',
                'code' => 201,
                'message' => $result->getMessage(),
            ];

            // Operation sucessful
            return $this->renderJson($response, $payload, 201); // We write the status code that will be injected in the payload.
        } else {

            throw new HttpBadRequestException($request, $result->getMessage(), $result->getErrors());
        }
    }

    /**
     * POST: Handles user log in
     * ROUTE: POST /login
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return Response
     */
    public function handleUserLogin(Request $request, Response $response): Response
    {
        //? Step 1) Get the body
        $users_data = $request->getParsedBody();

        //? Step 2) Handle if the body is empty
        if (empty($users_data)) {
            throw new HttpBadRequestException($request, "Data passed is empty");
        }

        if (!is_array($users_data) || count($users_data) !== 1) {
            throw new HttpBadRequestException($request, "Invalid data format, expected an array with a single user");
        }

        $user_data = $users_data[0]; // 1st user obj from array

        //? Step 4) Authenticate user
        try {

            $result = $this->user_service->authenticateUser(
                $user_data['email'],
                $user_data['password']
            );

            //? Step 5) If successful, continue to retrieve the payload
            if ($result->isSuccess()) {
                $user_info = $result->getData();
                $iat = time(); // Issued at
                $eat = $iat + 3600; // Expires at

                $registered_claim = [
                    'iss' => 'http://localhost/product-api',
                    'aud' => 'http://localhost/product-api',
                    'iat' => $iat,
                    'exp' => $eat,
                    "email" => $user_info['email'],
                    "id"    => $user_info['user_id'],
                    "isAdmin"  => $user_info['isAdmin'],
                ];

                //? Step 6) Generate a token for user log in
                $key = $this->appSettings->get("jwt_key");
                $jwt = JWT::encode($registered_claim, $key, 'HS256');

                //? Step 7) Throw successful response payload
                $success_response_payload = [
                    "status" => "Success",
                    "code" => 200,
                    "message" => "Login successful",
                    "token" => $jwt,
                    "expires" => $eat,
                    "user_id" => $user_info['user_id'],
                    "user_email" => $user_info['email'],
                    "isAdmin" => (int) $user_info['isAdmin'],
                    "firstname" => $user_info['first_name'],
                    "lastname" => $user_info['last_name']
                ];

                return $this->renderJson($response, $success_response_payload);
            } else {

                $error_response_payload = [
                    "status" => "Not authorized!",
                    "code" => 401,
                    "message" => $result->getMessage()
                ];

                //* 401 bc lack of auth creds
                return $this->renderJson($response, $error_response_payload, 401);
            }
        } catch (Exception $e) {

            $error_response_payload = [
                "status" => "Sever error!",
                "code" => 500,
                "message" => "Login error. Please try again."
            ];
            //* 500 bc unable to authenticate or handle user log in
            return $this->renderJson($response, $error_response_payload, 500);
        }
    }
}
