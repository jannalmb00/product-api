<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Exceptions\HttpInvalidInputException;
use App\Validation\ValidationHelper;
use App\Exceptions\HttpNoContentException;
use App\Models\AllergensModel;
use App\Models\BaseModel;

class AllergensController extends BaseController
{
    public function __construct(private AllergensModel $model)
    {
        //To initialize the validator
        parent::__construct();
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
        $info = $this->pagination($filters, $this->model, [$this->model, 'getAllergens']);

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

        if (preg_match($regex_id, $id) === 0) {
            throw new HttpInvalidInputException($request, "Provided product is invalid.");
        }

        //* paginate -- function from base controller
        $info = $this->pagination($filters, $this->model, [$this->model, 'getAllergenById']);

        if ($info["data"] == false) {
            //! no matching record in the db
            throw new HttpNoContentException($request, "Request successful. No allergen in the record.");
        }

        return $this->renderJson($response, $info);
    }

    public function handleGetIngredientsByAllergen(Request $request, Response $response, array $uri_args): Response
    {
        //* Get allergen ID from URI
        $allergen_id = $uri_args['allergen_id'];

        if (!isset($uri_args['allergen_id'])) {
            throw new HttpInvalidInputException($request, "Allergen ID is required in the URL");
        }

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

        //* Get ingredients by allergen with pagination
        $info = $this->pagination($filters, $this->model, [$this->model, 'getIngredientsByAllergen']);

        //* Check if any ingredients were found
        if ($info["data"] == false) {
            throw new HttpNoContentException($request, "Request successful. No ingredients found for this allergen.");
        }

        return $this->renderJson($response, $info);
    }
}
