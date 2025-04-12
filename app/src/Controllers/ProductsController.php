<?php

namespace App\Controllers;

use App\Services\ProductsService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Exceptions\HttpInvalidInputException;
use App\Validation\ValidationHelper;
use App\Exceptions\HttpNoContentException;
use App\Models\ProductsModel;
use App\Models\BaseModel;
use Slim\Exception\HttpBadRequestException;

/**
 * Controller responsible for handling methods related to products, such as retrieving list of products, specified products and retrieval of nutrition for a specified product
 */

class ProductsController extends BaseController
{

    private ValidationHelper $validator;

    public function __construct(private ProductsModel $model, private ProductsService $product_service)
    {
        //$this->validator = new ValidationHelper();

        //To initialize the validator
        parent::__construct();
    }

    /**
     * GET: Handles the request  of retrieving the products based on the filter parameter
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The request object containing the query parameter
     * @param \Psr\Http\Message\ResponseInterface $response The response object to return
     *
     * @throws \App\Exceptions\HttpNoContentException Throw when data is not found after all the filters
     * @return Response Response containing the list of allergens and its header
     */
    public function handleGetProducts(Request $request, Response $response): Response
    {
        //*Filters
        $filters = $request->getQueryParams();

        // //? Validation & exception handling of filter parameters

        //!NOTE: Can't add a Name filter for product that filters if the product name is only letters (ex: 2% milk)  -- need ideas

        //* Validating if input are string
        $stringValidateArray = ['product_name', 'product_origin', 'brand_name', 'category_name'];

        foreach ($stringValidateArray as $validateString) {

            //If filter array value is not empty
            if (!empty($filters[$validateString])) {
                $this->validateString($filters, $validateString, $request);
            }
        }

        //* paginate -- function from base controller
        $info = $this->pagination($filters, $this->model, [$this->model, 'getProducts']);

        if ($info["data"] == false) {
            //! no matching record in the db
            throw new HttpNoContentException($request, "Request successful. No product in the record.");
        }

        return $this->renderJson($response, $info);
    }

    /**
     *GET: Handles details of the specified product

     * @param \Psr\Http\Message\ServerRequestInterface $request The request object containing query parameter
     * @param \Psr\Http\Message\ResponseInterface $response The response object to return
     * @param array $uri_args The URI arguments containing ID
     *
     * @throws \App\Exceptions\HttpInvalidInputException Throw when input is not valid
     * @throws \App\Exceptions\HttpNoContentException Throw erroe when daa is empty after all the filters
     * @return Response Response containing the details of specified product
     */
    public function handleGetProductById(Request $request, Response $response, array $uri_args): Response
    {
        //*Get id from request
        $id = $uri_args['product_id'];

        //*Get parameters
        $filters = $request->getQueryParams();
        $filters['id'] = $id;

        //! REGEX - VALIDATION - EXCEPTIONS - ID
        $regex_id = '/^P\d{5,6}$/';

        if (preg_match($regex_id, $id) === 0) {
            throw new HttpInvalidInputException($request, "Provided product is invalid.");
        }

        $id = $this->validateFilterIds($filters, $regex_id, 'id', "Invalid Product ID input!", $request);

        //* paginate -- function from base controller
        $info = $this->pagination($filters, $this->model, [$this->model, 'getProductById']);

        if ($info["data"] == false) {
            //! no matching record in the db
            throw new HttpNoContentException($request, "Request successful. No product in the record.");
        }

        return $this->renderJson($response, $info);
    }

    /**
     * GET: Handles the retrieval of  nutritions for a specified product
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The request containing all the query parameter
     * @param \Psr\Http\Message\ResponseInterface $response The response object to return
     * @param array $uri_args The URI arguments containing the ID
     *
     * @throws \App\Exceptions\HttpInvalidInputException Throw error when invalid input is used/entered.
     * @throws \App\Exceptions\HttpNoContentException Throw error when data is empty
     * @return Response Response containing the details of the specified product
     */
    public function handleGetProductNutrition(Request $request, Response $response, array $uri_args): Response
    {
        //*Get id from request
        $id = $uri_args['product_id'];

        $filters = $request->getQueryParams();    // GET QUERY PARAMETERS
        $filters['id'] = $id;

        //? Graceful error handling
        $regex_id = '/^P\d{5,6}$/';

        if (preg_match($regex_id, $id) === 0) {
            throw new HttpInvalidInputException($request, "Provided product is invalid. Ingredients records cannot be retrieved.");
        }

        $id = $this->validateFilterIds($filters, $regex_id, 'id', "Invalid Category ID input!", $request);

        // $this->model->setPaginationOptions($filters["page"], $filters["page_size"]);

        $info = $this->pagination($filters, $this->model, [$this->model, 'getProductByNutrition']);

        if ($info["data"] == false) {
            //! no matching record in the db
            throw new HttpNoContentException($request, "No matching record.");
        }

        return $this->renderJson($response, $info);
    }

    /**
     * POST: Handles creation of a product
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request refers to the request object
     * @param \Psr\Http\Message\ResponseInterface $response refers to the response object
     * @throws \Slim\Exception\HttpBadRequestException refers to the bad request if body is empty
     * @return Response refers to the result
     */
    public function handleCreateProducts(Request $request, Response $response): Response
    {
        //? Step 1) GET the parsed BODY
        $product_data = $request->getParsedBody();

        //? Step 2) HANDLE the error if the body if its empty
        if (empty($product_data)) {
            throw new HttpBadRequestException($request, "Data passed is empty");
        }

        //? Step 3) CALL the service
        $result = $this->product_service->createProducts($product_data);

        //! Note verify the outcome of the opertion: success vs failure
        if ($result->isSuccess()) {

            $payload = [
                'status' => 'success',
                'code' => 201,
                'message' => $result->getMessage(),
                'data' => $result->getData()
            ];

            //? Step 3) Return the response payload
            return $this->renderJson($response, $payload, 201);
        } else {

            $payload = [
                'status' => 'error',
                'code' => 400,
                'message' => $result->getMessage(),
                'details' => $result->getErrors()
            ];

            //? Step 3) Return the response payload
            return $this->renderJson($response, $payload, 400);
        }
    }

    /**
     * PUT: Handles updates on an existing product
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request refers to the request object
     * @param \Psr\Http\Message\ResponseInterface $response refers to the response object
     * @param array $uri_args based on the URI args
     * @throws \Slim\Exception\HttpBadRequestException refers to the bad request if parsed body is empty
     * @return Response refers to the result
     */
    public function handleUpdateProduct(Request $request, Response $response, array $uri_args)
    {
        $product_data = $request->getParsedBody();

        if (empty($product_data)) {
            throw new HttpBadRequestException($request, "Data passed is empty");
        }

        $result = $this->product_service->updateProduct($product_data);

        if ($result->isSuccess()) {

            $payload = [
                'status' => 'success',
                'code' => 200,
                'message' => $result->getMessage()
            ];

            return $this->renderJson($response, $payload, 200);
        } else {

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
     * DELETE: Handles the deletion of an existing product
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request refers to the request object
     * @param \Psr\Http\Message\ResponseInterface $response refers to the response object
     * @param array $uri_args refers to the URI arguments
     * @throws \Slim\Exception\HttpBadRequestException refers to the bad request if the request body is empty
     * @return Response refers to the result
     */
    public function handleDeleteProduct(Request $request, Response $response, array $uri_args): Response
    {

        $product_ids = $request->getParsedBody();

        if (empty($product_ids)) {
            throw new HttpBadRequestException($request, "Product ID is required");
        }

        $result = $this->product_service->deleteProduct($product_ids);

        if ($result->isSuccess()) {
            $payload = [
                'status' => 'success',
                'code' => 201,
                'message' => $result->getMessage()
            ];
            return $this->renderJson($response, $payload, 201);
        } else {
            $payload = [
                'status' => 'error',
                'code' => 400,
                'message' => $result->getMessage(),
                'details' => $result->getErrors()
            ];
            return $this->renderJson($response, $payload, 400);
        }
    }
}
