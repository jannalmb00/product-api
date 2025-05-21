<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Exceptions\HttpInvalidInputException;
use App\Exceptions\HttpNoContentException;
use App\Models\CategoriesModel;
use App\Services\CategoriesService;
use Slim\Exception\HttpBadRequestException;


/**
 * Controller resonsible for handling methods related to categories, such as retrieving list of categories, specified category and filtered categories based on brand
 */
class CategoriesController extends BaseController
{
    /**
     * Categories controller constructor sets up the controllr with a model and service is used to get category data
     *
     * @param \App\Models\CategoriesModel $model
     */
    public function __construct(private CategoriesModel $model, private CategoriesService $service)
    {
        //To initialize the validator
        parent::__construct();
    }

    /**
     * POST: Handles the creation of new categories
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request refers to the request object
     * @param \Psr\Http\Message\ResponseInterface $response refers to the response object
     * @return Response refers to the result
     */
    public function handleCreateCategories(Request $request, Response $response): Response
    {

        // Extract the JSON data from the request
        $category_data = $request->getParsedBody();

        //POST - in json
        //? the body could be empty. handle case where body is empty ,,, when request is returned null or invalid
        if (empty($category_data)) {
            throw new HttpBadRequestException($request, "Data passed is empty");
        }

        //? CALL SERVICE-  Pass the category_data array to service
        $result = $this->service->createCategories($category_data);

        //!Note verify he outcome of the operation: success vs failure
        if ($result->isSuccess()) {
            //OPeration succeeded.
            $payload = [
                'status' => 'Success',
                'code' => 201,
                'message' => $result->getMessage(),
            ];
            //override the default 200 satus code to 201
            return  $this->renderJson($response, $payload, 201);
        } else {
            // If unsuccessful, throw exception
            throw new HttpBadRequestException($request, $result->getMessage());
        }
    }

    /**
     * PUT: Handles the existing update of a category
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request refers to the request object
     * @param \Psr\Http\Message\ResponseInterface $response refers to the response object
     * @throws \Slim\Exception\HttpBadRequestException refers to the bad request if the request body is empty
     * @return Response refers to the result
     */
    public function handleUpdateCategories(Request $request, Response $response): Response
    {
        // Etract JSON_body data from the request
        $category_data = $request->getParsedBody();

        //POST - in json
        //? the body could be empty. handle case where body is empty ,,, when request is returned null or invalid
        if (empty($category_data)) {
            throw new HttpBadRequestException($request, "Data passed is empty. Nothing to update.");
        }

        //? CALL SERVICE
        $result = $this->service->updateCategory($category_data[0]);

        //!NOte verify he outcome of the operation: success vs failure
        if ($result->isSuccess()) {
            //OPeration succeeded.

            $payload = [
                'status' => 'Success',
                'code' => 201,
                'message' => $result->getMessage(),

            ];
            //override the default 200 satus code to 201
            return  $this->renderJson($response, $payload, 201);
        } else {

            // Unsuccessful
            $payload = [
                'status' => 'error',
                'code' => 400,
                'message' => $result->getMessage(),
                'details' => $result->getErrors()
            ];

            return $this->renderJson($response, $payload, 400);
        }
    }


    /**
     * DELETE: Handles the deletion of an existing category
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request refers to the request object
     * @param \Psr\Http\Message\ResponseInterface $response refers to the response object
     * @throws \Slim\Exception\HttpBadRequestException refers to the bad request if the request body is empty
     * @return Response refers to the result
     */
    public function handleDeleteCategories(Request $request, Response $response): Response
    {
        // Extract the data from the request body
        $allergen_ids = $request->getParsedBody();

        //Validate if ID_is present
        if (empty($allergen_ids)) {
            throw new HttpBadRequestException($request, "Allergen ID is required");
        }

        //call service that process deletion
        $result = $this->service->deleteCategories($allergen_ids);

        //Evaluate the result
        if ($result->isSuccess()) {

            $payload = [
                'status' => 'success',
                'code' => 201,
                'message' => $result->getMessage(),
            ];
            // Operation successful
            return $this->renderJson($response, $payload, 201);
        }
        //! Operation failed.
        $payload = [
            'status' => 'error',
            'code' => 404,
            'message' => $result->getMessage(),
            'details' => $result->getErrors(),
        ];
        return $this->renderJson($response, $payload, 400);
    }

    /**
     * GET: Handles the request of retrieving categories based in the filter parameter
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The request object containing query parameter
     * @param \Psr\Http\Message\ResponseInterface $response The response object to return
     *
     * @throws \App\Exceptions\HttpNoContentException Throw when data is not found after all the filters
     * @return Response Response containing the list of categories and its header
     */
    public function handleGetCategories(Request $request, Response $response): Response
    {
        //*Filters:Extratc query param
        $filters = $request->getQueryParams();

        // //? Validation & exception handling of filter parameters
        //* Validating if filter input are string
        $stringValidateArray = ['category_type', 'category_name', 'parent_category'];

        foreach ($stringValidateArray as $validateString) {
            //If filter array value is not empty
            if (!empty($filters[$validateString])) {

                $this->validateString($filters, $validateString, $request);
            }
        }

        //* paginate -- function from base controller
        $info = $this->pagination($filters, $this->model, [$this->model, 'getCategories'], $request);

        //! VALIDATION
        if ($info["data"] == false) {
            //! no matching record in the db
            throw new HttpNoContentException($request, "Request successful. No product in the record.");
        }

        return $this->renderJson($response, $info);
    }

    /**
     * GET: Handles details of the specified category
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The request object containing query parameter
     * @param \Psr\Http\Message\ResponseInterface $response The response object to return
     * @param array $uri_args The URI argument containing ID
     *
     * @throws \App\Exceptions\HttpInvalidInputException Throw when input is invalid
     * @throws \App\Exceptions\HttpNoContentException Throw when data is empty after all the filters
     * @return Response Response containing the details of the specified category
     */
    public function handleGetCategoryById(Request $request, Response $response, array $uri_args): Response
    {
        //*Get id from request
        $id = $uri_args['category_id'];

        //*Get parameters
        $filters = $request->getQueryParams();
        $filters['id'] = $id;

        //! REGEX - VALIDATION - EXCEPTIONS - ID
        $regex_id = '/^C-\d{4,5}$/';

        if (preg_match($regex_id, $id) === 0) {
            throw new HttpInvalidInputException($request, "Provided category is invalid.");
        }

        $this->validateFilterIds($filters, $regex_id, 'id', "Invalid Category ID input!", $request);

        //* paginate -- function from base controller
        $info = $this->pagination($filters, $this->model, [$this->model, 'getCategoryById'], $request);

        if ($info["data"] == false) {
            //! no matching record in the db
            throw new HttpNoContentException($request, "Request successful. No category in the record.");
        }

        return $this->renderJson($response, $info);
    }

    /**
     * GET: Handles the retrieval of brands for a specified category
     * @param \Psr\Http\Message\ServerRequestInterface $request The request containing all the query parameter
     * @param \Psr\Http\Message\ResponseInterface $response The response object to  return
     * @param array $uri_args The URI arguments containing ID
     *
     * @throws \App\Exceptions\HttpInvalidInputException Throw error when invalid input is used/entered
     * @throws \App\Exceptions\HttpNoContentException Throw error when data is empty
     * @return Response Response containing the details of specified category
     */
    public function handleGetBrandsByCategory(Request $request, Response $response, array $uri_args): Response
    {

        //* See if there is a category ID included in the URL
        if (!isset($uri_args['category_id'])) {
            throw new HttpInvalidInputException($request, "Category ID is required in the URL");
        }

        //* Get category id from request
        $category_id = $uri_args['category_id'];

        //* Get query params
        $filters = $request->getQueryParams();
        $filters['id'] = $category_id;

        // Validating id
        $regex_id = '/^C-\d{4,5}$/';

        $this->validateFilterIds($filters, $regex_id, 'id', "Provided category ID is invalid.Invalid Category ID input!", $request);


        //* Validate string parameters if they are provided
        $stringValidateArray = ['brand_name', 'brand_country'];

        foreach ($stringValidateArray as $validateString) {
            if (!empty($filters[$validateString])) {
                $this->validateString($filters, $validateString, $request);
            }
        }

        //* Get brands by category with pagination
        $filters['category_id'] = $category_id;
        $info = $this->pagination($filters, $this->model, [$this->model, 'getBrandsByCategory'], $request);


        //* Check if any brands were found
        if ($info["data"] == false) {
            throw new HttpNoContentException($request, "Request successful. No brands found for this category.");
        }

        return $this->renderJson($response, $info);
    }
}
