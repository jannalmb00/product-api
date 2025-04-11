<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Exceptions\HttpInvalidInputException;
use App\Validation\ValidationHelper;
use App\Exceptions\HttpNoContentException;
use App\Models\CategoriesModel;
use App\Models\BaseModel;
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
     * POST
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return Response
     */
    public function handleCreateCategories(Request $request, Response $response): Response
    {
        $new_category = $request->getParsedBody();

        //POST - in json
        //? the body could be empty. handle case where body is empty ,,, when request is returned null or invalid
        if (empty($new_category)) {
            throw new HttpBadRequestException($request, "Data passed is empty");
        }
        //Pass the category_data array to service
        //? CALL SERVICE
        $result = $this->service->createCategories($new_category);

        //!NOte verify he outcome of the opertion: sucess vs filure
        if ($result->isSuccess()) {
            //OPeration succeeded.
            // create an array that will contain -- make this array reusable
            $payload = [
                'status' => 'Success',
                'code' => 201,
                'message' => $result->getMessage(),
            ];
            //override the default 200 satus code to 201
            return  $this->renderJson($response, $payload, 201);
        } else {
            // If unsuccessful, throw exception
            throw new HttpBadRequestException($request, $result->getMessage(), $result->getErrors());
        }


        // DO FAILED OPERATION
        //Return a failed operation

        //TODO prepare and return a response containing the "status, message, code, details"
        //TODO structure repsonse as shown in class and return as JSON response
        // $payload = [
        //     'status' => 'error',
        //     'code' => 400,
        //     'message' => $result->getMessage(),
        //     'details' => $result->getErrors()
        // ];
        // return  $this->renderJson($response, $payload, 400);
    }

    public function handleUpdateCategories(Request $request, Response $response): Response
    {
        // category to update
        $update_category = $request->getParsedBody();

        //dd($update_category);
        //POST - in json
        //? the body could be empty. handle case where body is empty ,,, when request is returned null or invalid
        if (empty($update_category)) {
            throw new HttpBadRequestException($request, "Data passed is empty. Nothing to update.");
        }
        //Pass the category_data array to service
        //? CALL SERVICE
        $result = $this->service->updateCategory($update_category);

        //!NOte verify he outcome of the opertion: sucess vs filure
        if ($result->isSuccess()) {
            //OPeration succeeded.
            // create an array that will contain -- make this array reusable
            $payload = [
                'status' => 'Success',
                'code' => 201,
                'message' => $result->getMessage(),
            ];
            //override the default 200 satus code to 201
            return  $this->renderJson($response, $payload, 201);
        } else {
            // If unsuccessful, throw exception
            throw new HttpBadRequestException($request, $result->getMessage(), $result->getErrors());
        }


        // DO FAILED OPERATION
        //Return a failed operation

        //TODO prepare and return a response containing the "status, message, code, details"
        //TODO structure repsonse as shown in class and return as JSON response
        // $payload = [
        //     'status' => 'error',
        //     'code' => 400,
        //     'message' => $result->getMessage(),
        //     'details' => $result->getErrors()
        // ];
        // return  $this->renderJson($response, $payload, 400);
    }


    public function handleDeleteCategories(Request $request, Response $response): Response
    {
        ///$id = $uri_args['allergen_id'];
        $allergen_ids = $request->getParsedBody();
        // NOTE: removes an element from an array: by its index or by its key.
        //unset($allergen_ids[0]);
        //dd($allergen_ids);

        if (empty($allergen_ids)) {
            throw new HttpBadRequestException($request, "Allergen ID is required");
        }
        $result = $this->service->deleteCategory($allergen_ids);

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
        //*Filters
        $filters = $request->getQueryParams();

        // //? Validation & exception handling of filter parameters
        //* Validating if filter input are string
        $stringValidateArray = ['category_name', 'category_type', 'parent_category'];

        foreach ($stringValidateArray as $validateString) {
            //If filter array value is not empty
            if (!empty($filters[$validateString])) {

                $this->validateString($filters, $validateString, $request);
            }
        }

        //* paginate -- function from base controller
        $info = $this->pagination($filters, $this->model, [$this->model, 'getCategories']);

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

        // if (preg_match($regex_id, $id) === 0) {
        //     throw new HttpInvalidInputException($request, "Provided product is invalid.");
        // }

        $id = $this->validateFilterIds($filters, $regex_id, 'id', "Invalid Category ID input!", $request);

        //* paginate -- function from base controller
        $info = $this->pagination($filters, $this->model, [$this->model, 'getCategoryById']);

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

        // REGEX - VALIDATION - EXCEPTIONS - CATEGORY ID -> THIS IS THE INPUT VALIDATION if we needed have to double check
        $regex_id = '/^C-\d{4,5}$/';
        // if (preg_match($regex_id, $category_id) === 0) {
        //     throw new HttpInvalidInputException($request, "Provided category ID is invalid.");
        // }

        $category_id = $this->validateFilterIds($filters, $regex_id, 'id', "Invalid Category ID input!", $request);


        //* Validate string parameters if they are provided
        $stringValidateArray = ['brand_name', 'brand_country'];

        foreach ($stringValidateArray as $validateString) {
            if (!empty($filters[$validateString])) {
                $this->validateString($filters, $validateString, $request);
            }
        }

        //* Get brands by category with pagination
        $filters['category_id'] = $category_id;
        $info = $this->pagination($filters, $this->model, [$this->model, 'getBrandsByCategory']);


        //* Check if any brands were found
        if ($info["data"] == false) {
            throw new HttpNoContentException($request, "Request successful. No brands found for this category.");
        }

        return $this->renderJson($response, $info);
    }
}
