<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Exceptions\HttpInvalidInputException;
use App\Validation\ValidationHelper;
use App\Exceptions\HttpNoContentException;
use App\Models\CategoriesModel;
use App\Models\BaseModel;

class CategoriesController extends BaseController
{
    public function __construct(private CategoriesModel $model)
    {
        //To initialize the validator
        parent::__construct();
    }

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
            throw new HttpInvalidInputException($request, "Provided product is invalid.");
        }

        //* paginate -- function from base controller
        $info = $this->pagination($filters, $this->model, [$this->model, 'getCategoryById']);

        if ($info["data"] == false) {
            //! no matching record in the db
            throw new HttpNoContentException($request, "Request successful. No category in the record.");
        }

        return $this->renderJson($response, $info);
    }

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
        //  $regex_id = '/^C-\d{4,5}$/';
        // if (preg_match($regex_id, $category_id) === 0) {
        //     throw new HttpInvalidInputException($request, "Provided category ID is invalid.");
        // }

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
