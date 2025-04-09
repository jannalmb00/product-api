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

/**
 * Controller responsible for handling methods related to products, such as retrieving list of products, specified products and retrieval of nutrition for a specified product
 */

class ProductsController extends BaseController
{

    private ValidationHelper $validator;

    public function __construct(private ProductsModel $model, private ProductsService $service)
    {
        //$this->validator = new ValidationHelper();

        //To initialize the validator
        parent::__construct();
    }
    // public function __construct(private ProductsModel $model)
    // {
    //     //$this->validator = new ValidationHelper();

    //     //To initialize the validator
    //     parent::__construct();
    // }

    public function handleCreateProducts(Request $request, Response $response): Response
    {

        echo ' blet';

        //POST - in json
        // TODO: the body could be empty. handle case where body is empty ,,, when request is returned null or invalid
        $new_product = $request->getParsedBody();




        // dd($new_product);

        //? CALL SERVICE
        $result = $this->service->createProducts($new_product);
        if ($new_product != NULL) {
            dd($new_product);

            //!NOte verify he outcome of the opertion: sucess vs filure
            if ($result->isSuccess()) {
                //OPeration succeeded.
                // create an array that will contain -- make this array reusable
                $payload = [
                    'status' => 'Success',
                    'code' => 200,
                    'message' => $result->getMessage(),
                ];

                //pverride the default 200 satus code to 201
                return  $this->renderJson($response, $payload, 201);
            }
        }

        // DO FAILED OPERATION
        //Return a failed operation

        //TODO prepare and return a response containing the "status, message, code, details"
        //TODO structure repsonse as shown in class and return as JSON response
        $payload = [
            'status' => 'error',
            'code' => 400,
            'message' => $result->getMessage(),
            'details' => $result->getErrors()
        ];
        return  $this->renderJson($response, $payload, 400);
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

        // if (isset($filters['name'])) {

        //     $filter = $filters['name'];
        //     $string_valid = $this->validator->isAlpha($filter);

        //     if (!$string_valid) {
        //         throw new HttpInvalidInputException($request, "Invalid input. No numbers allowed");
        //     }
        // }

        // if (isset($filters['last_name'])) {

        //     $filter = $filters['last_name'];

        //     //Checks if string is just letters and no numbers
        //     $string_valid = $this->validator->isAlpha($filter);

        //     if (!$string_valid) {
        //         throw new HttpInvalidInputException($request, "Invalid input. No numbers allowed.");
        //     }
        // }
        // }


        // if (isset($filters['position'])) {

        //     $filter = $filters['position'];

        //     $string_valid = $this->validator->isAlpha($filter);

        //     $valid_positions = ["goal keeper", "forward", "defender", "midfielder"];

        //     if (!$string_valid && (!in_array($filters['position'], $valid_positions))) {
        //         throw new HttpInvalidInputException($request, "Invalid input. Position are only defender, midfielder, goal keeper or forward.");
        //     }
        // }
        // if (isset($filters['gender'])) {

        //     $filter = $filters['gender'];

        //     $string_valid = $this->validator->isAlpha($filter);

        //     $valid_positions = ["male", "female"];

        //     if (!$string_valid || !in_array($filter, $valid_positions)) {
        //         throw new HttpNoContentException($request, "Invalid input. Gender is only female or male.");
        //     }
        // }



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


        // //? Validation & exception handling of filter parameters
        // $this->validateFilterIds($filters, $regex_id, '');

        // // if (isset($filters['tournament_id'])) {
        // //     $filter = $filters['tournament_id'];
        // //     $regex_tour_id = '/^WC-\d{4}$/';
        // //     if (preg_match($regex_tour_id, $filter) === 0) {
        // //         throw new HttpInvalidInputException($request, "Invalid input. Provided tournament id is invalid.");
        // //     }
        // // }
        // if (isset($filters['match_id'])) {

        //     $filter = $filters['match_id'];
        //     $regex_tour_id = '/^M-\d{4}-\d{2}$/';

        //     if (preg_match($regex_tour_id, $filter) === 0) {
        //         throw new HttpInvalidInputException($request, "Invalid input. Provided match id is invalid.");
        //     }
        // }


        // $this->model->setPaginationOptions($filters["page"], $filters["page_size"]);

        $info = $this->pagination($filters, $this->model, [$this->model, 'getProductByNutrition']);

        if ($info["data"] == false) {
            //! no matching record in the db
            throw new HttpNoContentException($request, "No matching record.");
        }

        return $this->renderJson($response, $info);
    }
}
