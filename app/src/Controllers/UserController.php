<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Exceptions\HttpInvalidInputException;
use App\Validation\ValidationHelper;
use App\Exceptions\HttpNoContentException;
use App\Models\UserModel;
use App\Models\BaseModel;
use App\Services\UserService;
use Slim\Exception\HttpBadRequestException;

/**
 * Controller responsible for handling methods related to allergens, such as retrieving list of allergens' detals, specified allergen, and  retrieval of ingredients for a specified allergen..
 */
class UserController extends BaseController
{

    public function __construct(private UserModel $user_model, private UserService $user_service) // replace the reference to a service, and the service will have a reference to the model
    {
        //To initialize the validator
        parent::__construct();
    }


    //* ROUTE: POST /Register
    /**
     * POST:
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return Response
     */
    public function handleCreateRegister(Request $request, Response $response): Response
    {

        //TODO: Handle case where the case where the body could be empty
        //$request->getBody();

        $users_data = $request->getParsedBody();

        if (empty($users_data)) {
            throw new HttpBadRequestException($request, "Data passed is empty");
        }

        if (!is_array($users_data) || count($users_data) !== 1) {
            throw new HttpBadRequestException($request, "Invalid data format, expected an array with a single user object");
        }

        $user_data = $users_data[0];



        // dd($allergens_data);
        $result = $this->user_service->createUser($user_data);
        //dd($result);
        //* Dont forget to identify the outcome of the operations: success vs failure
        if ($result->isSuccess()) {
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

        /*
        Write the rules ;
        */
        // Return a failed operation.
        // TODO: You need to prepare (structure the response as shown in class) the bad request: 400 BAD REQUEST and return the JSON response -> YOU SET THE CODE IN CONTROLLER (PREPARED PAYLOAD IN BASE CONTROLLER)

        // 400 bad request


    }
}
