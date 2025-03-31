<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Exceptions\HttpInvalidInputException;
use App\Validation\ValidationHelper;
use App\Exceptions\HttpNoContentException;
use App\Models\AllergensModel;
use App\Models\BaseModel;
use App\Services\AllergensService;

class AllergensController extends BaseController
{
    public function __construct(private AllergensModel $allergens_model, private AllergensService $allergens_service) // replace the reference to a service, and the service will have a reference to the model
    {
        //To initialize the validator
        parent::__construct();
    }

    //* ROUTE: POST /ALLERGENS
    public function handleCreateAllergens(Request $request, Response $response): Response
    {

        echo 'HELLO';

        //TODO: Handle case where the case where the body could be empty
        $request->getBody();

        $allergens_data = $request->getParsedBody();

        // dd($allergens_data);
        $result = $this->allergens_service->createAllergens($allergens_data);

        //* Dont forget to identify the outcome of the operations: success vs failure
        if ($result->isSuccess()) {
            // Operation success
            $payload = [
                'status' => 'success',
                'code' => 201,
                'message' => $result->getMessage(),
            ];
            // Operation sucessful
            $this->renderJson($response, $payload, 201); // We write the status code that will be injected in the payload.
        }

        /*
        Write the rules ;
        */
        // Return a failed operation.
        // TODO: You need to prepare (structure the response as shown in class) the bad request: 400 BAD REQUEST and return the JSON response -> YOU SET THE CODE IN CONTROLLER (PREPARED PAYLOAD IN BASE CONTROLLER)

        // 400 bad request
        $payload = [
            'status' => 'failure',
            'code' => 400,
            'message' => $result->getMessage(),
            'details' => $result->getErrors(),
        ];
        return $this->renderJson($response, $payload, 400);
    }
    public function handleGetAllergens(Request $request, Response $response): Response
    {
        //*Filters
        $filters = $request->getQueryParams();

        // //? Validation & exception handling of filter parameters
        //* Validating if filter input are string
        //! MISSING ALLERGEN_REACTION_TYPE - NOT SURE OF THAT AS A FILTER
        $stringValidateArray = ['allergen_name', 'food_group', 'food_type', 'food_origin', 'food_item'];

        foreach ($stringValidateArray as $validateString) {
            //If filter array value is not empty
            if (!empty($filters[$validateString])) {
                //    dd($filters);
                //  $this->validateString($filters, (string) $filters[$validateString], $request);
                $this->validateString($filters, $validateString, $request);
            }
        }

        //* paginate -- function from base controller
        $info = $this->pagination($filters, $this->allergens_model, [$this->allergens_model, 'getAllergens']);

        //! VALIDATION
        if ($info["data"] == false) {
            //! no matching record in the db
            throw new HttpNoContentException($request, "Request successful. No product in the record.");
        }

        return $this->renderJson($response, $info);
    }

    public function handleGetAllergenById(Request $request, Response $response, array $uri_args): Response
    {
        //* Get id from request
        $id = $uri_args['allergen_id'];

        //*Get parameters
        $filters = $request->getQueryParams();
        $filters['id'] = $id;

        //! REGEX - VALIDATION - EXCEPTIONS - ID
        $regex_id = '/^A\d{2,3}$/';

        // if (preg_match($regex_id, $id) === 0) {
        //     throw new HttpInvalidInputException($request, "Provided product is invalid.");
        // }

        $id = $this->validateFilterIds($filters, $regex_id, 'id', "Invalid Allergen ID input!", $request);

        //* paginate -- function from base controller
        $info = $this->pagination($filters, $this->allergens_model, [$this->allergens_model, 'getAllergenById']);

        if ($info["data"] == false) {
            //! no matching record in the db
            throw new HttpNoContentException($request, "Request successful. No allergen in the record.");
        }

        return $this->renderJson($response, $info);
    }

    public function handleGetIngredientsByAllergen(Request $request, Response $response, array $uri_args): Response
    {

        if (!isset($uri_args['allergen_id'])) {
            throw new HttpInvalidInputException($request, "Allergen ID is required in the URL");
        }


        //* Get allergen ID from URI
        $allergen_id = $uri_args['allergen_id'];

        //* Get query parameters
        $filters = $request->getQueryParams();
        $filters['allergen_id'] = $allergen_id;

        //* Validate string params
        $stringValidateArray = ['ingredient_name', 'processing_type'];

        foreach ($stringValidateArray as $validateString) {
            if (!empty($filters[$validateString])) {
                $this->validateString($filters, $validateString, $request);
            }
        }

        $regex_id =  '/^A\d{2,3}$/';

        $allergen_id = $this->validateFilterIds($filters, $regex_id, 'allergen_id', "Invalid Allergen ID Input.", $request);

        // Validate isGMO parameter
        if (isset($filters['isGMO']) && !in_array($filters['isGMO'], ['0', '1'])) {
            throw new HttpInvalidInputException($request, "isGMO parameter must be either 0 or 1.");
        }

        //* Get ingredients by allergen with pagination
        $info = $this->pagination($filters, $this->allergens_model, [$this->allergens_model, 'getIngredientsByAllergen']);

        //* Check if any ingredients were found
        if ($info["data"] == false) {
            throw new HttpNoContentException($request, "Request successful. No ingredients found for this allergen.");
        }

        return $this->renderJson($response, $info);
    }
}
