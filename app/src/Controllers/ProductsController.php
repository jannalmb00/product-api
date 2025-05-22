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

            if (!empty($filters[$validateString])) {
                $this->validateString($filters, $validateString, $request);
            }
        }

        //  dd($filters);

        //* paginate -- function from base controller
        $info = $this->pagination($filters, $this->model, [$this->model, 'getProducts'], $request);

        if ($info["data"] == false) {
            //! no matching record in the db
            throw new HttpNoContentException($request, "Request successful. No product in the record.");
            // $payload = [
            //     "status" => "Success",
            //     "message" => "Request successful. No matching data found.",
            //     "data" => []
            // ];
            // $response->getBody()->write(json_encode($payload));
            // return $response->withStatus(200);
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

        //* Filter validation - id
        $regex_id = '/^P\d{5,6}$/';

        $this->validateFilterIds($filters, $regex_id, 'id', "Provided product ID is invalid.", $request);

        //* paginate -- function from base controller
        $info = $this->pagination($filters, $this->model, [$this->model, 'getProductById'],  $request);

        if ($info["data"] == false) {
            //! no matching record in the db - does not include message body by design
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
        //*Check if id from request
        if (!isset($uri_args['product_id'])) {
            throw new HttpInvalidInputException($request, "Product ID is required in the URL");
        }

        //* Get product ID from URI
        $product_id = $uri_args['product_id'];

        //* Get query parameters
        $filters = $request->getQueryParams();
        $filters['id'] = $product_id;

        //* Filter validation -
        $regex_id = '/^P\d{5,6}$/';

        $this->validateFilterIds($filters, $regex_id, 'id', "Provided product ID is invalid. Ingredient records cannot be retrieved.", $request);

        //* Pagination
        $info = $this->pagination($filters, $this->model, [$this->model, 'getProductByNutrition'],  $request);

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
        $result = $this->product_service->createProducts($product_data[0]);

        //! Note verify the outcome of the opertion: success vs failure
        if ($result->isSuccess()) {

            $payload = [
                'status' => 'success',
                'code' => 201,
                'message' => $result->getMessage(),
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
    public function handleUpdateProduct(Request $request, Response $response)
    {
        //retrieve data from the request body
        $product_data = $request->getParsedBody();

        if (empty($product_data)) {
            throw new HttpBadRequestException($request, "Data passed is empty");
        }
        //? CALL SERVICE
        $result = $this->product_service->updateProduct($product_data[0]);

        //return a JSON_response depending on the result process
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

            // If unsuccessful, throw exception
            // throw new HttpBadRequestException($request, $result->getMessage(), $result->getErrors());
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
        //retrieve id from the request body
        $product_ids = $request->getParsedBody();
        //validate that id is provided
        if (empty($product_ids)) {
            throw new HttpBadRequestException($request, "Product ID is required");
        }
        //? CALL SERVICE
        $result = $this->product_service->deleteProduct($product_ids);

        //return a JSON_response depending on the result of the deletion process
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
