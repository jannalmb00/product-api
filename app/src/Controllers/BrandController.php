<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Exceptions\HttpInvalidInputException;
use App\Validation\ValidationHelper;
use App\Exceptions\HttpNoContentException;
use App\Models\BrandModel;
use App\Models\BaseModel;
use App\Services\BrandService;
use Slim\Exception\HttpBadRequestException;

/**
 * Controller responsible for handling methods related to allergens, such as retrieving list of allergens' detals, specified allergen, and  retrieval of ingredients for a specified allergen..
 */
class BrandController extends BaseController
{
    /**
     * Allergen Controller constructor sets up the controller with a  model and service are used to get allergen data
     *
     * @param \App\Models\BrandModel $brandModel
     *
     */
    public function __construct(private BrandModel $brandModel)
    {
        //To initialize the validator
        parent::__construct();
    }

    /**
     * GET: Handles the request of retrievin products of a specified brand
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The request object containing query parameter
     * @param \Psr\Http\Message\ResponseInterface $response The response object to return
     *
     * @throws \App\Exceptions\HttpNoContentException Throw when data is not found after all the filters
     * @return Response
     */
    public function handleGetProductsByBrand(Request $request, Response $response, array $uri_args): Response
    {

        //* See if there is a brand ID included in the URL
        if (!isset($uri_args['brand_id'])) {
            throw new HttpInvalidInputException($request, "Brand ID is required in the URL");
        }

        //* Get brand id from request
        $brand_id = $uri_args['brand_id'];

        //* Get query params
        $filters = $request->getQueryParams();
        $filters['brand_id'] = $brand_id;

        // REGEX - VALIDATION - EXCEPTIONS - brand ID -> THIS IS THE INPUT VALIDATION if we needed have to double check
        $regex_id = '/^B\d{4,5}$/';


        $this->validateFilterIds($filters, $regex_id, 'brand_id', "Provided brand ID is invalid.Invalid brand ID input!", $request);


        //* Validate string parameters if they are provided
        $stringValidateArray = ['product_name', 'product_origin', 'category_name'];

        foreach ($stringValidateArray as $validateString) {
            if (!empty($filters[$validateString])) {
                $this->validateString($filters, $validateString, $request);
            }
        }

        //* Get brands by category with pagination
        $filters['brand_id'] = $brand_id;
        $info = $this->pagination($filters, $this->brandModel, [$this->brandModel, 'getProductsByBrand'], $request);


        //* Check if any brands were found
        if ($info["data"] == false) {
            throw new HttpNoContentException($request, "Request successful. No brands found for this category.");
        }

        return $this->renderJson($response, $info);
    }
}
