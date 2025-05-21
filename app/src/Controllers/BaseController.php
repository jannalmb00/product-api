<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\BaseModel;
use App\Validation\ValidationHelper;
use App\Validation\Validator;
use App\Exceptions\HttpInvalidInputException;
use App\Exceptions\HttpNoContentException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
//use Psr\Http\Message\ResponseInterface as Response;
//use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Proides shared utility methods for all controllers
 */
abstract class BaseController
{
    private ValidationHelper $validator;

    public function __construct()
    {
        //Validator Helper
        $this->validator = new ValidationHelper();
    }

    /**
     * Sends a JSON response with appropriate headers and status code
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array $data
     * @param int $status_code
     * @return Response
     */
    protected function renderJson(Response $response, array $data, int $status_code = 200): Response
    {
        $payload = json_encode($data, JSON_UNESCAPED_SLASHES |    JSON_PARTIAL_OUTPUT_ON_ERROR);
        $response->getBody()->write($payload);
        return $response->withStatus($status_code)->withAddedHeader(HEADERS_CONTENT_TYPE, APP_MEDIA_TYPE_JSON);
    }


    //! PAGINATION
    /**
     * Handles pagination logic and data fetching
     *
     * @param array $filters  Query parameters from the request.
     * @param \App\Models\BaseModel $model The model object to apply pagination on.
     * @param callable $method
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @throws \App\Exceptions\HttpInvalidInputException
     * @return mixed
     */
    public function pagination(array $filters, BaseModel $model, callable $method,  Request $request): mixed
    {

        //Add a default if no page and page size set
        $page = isset($filters["page"]) ? (int) $filters["page"] : 1;
        $size = isset($filters["page_size"]) ? (int) $filters["page_size"] : 5;
        // Validate page params
        $rules = array(

            'page' => [
                ['min', '1']
            ],
            "page_size" => [
                ['min', '1']
            ]
        );

        // If input is non-numeric, the value is 0
        $page_data = [
            'page' => isset($filters["page"]) ? (string) $filters["page"] : '1',
            'page_size' => isset($filters["page_size"]) ? (string) $filters["page_size"] : '10'
        ];

        // Validation
        $validator = new Validator($page_data, [], 'en');;
        $validator->mapFieldsRules($rules);


        if (!$validator->validate()) {

            throw new HttpInvalidInputException($request, "Invalid input. Page and page size must be an integer greater than or equal to 1.");
        } else {

            // Apply pagination settings to the model
            $model->setPaginationOptions((int) $page_data['page'], (int) $page_data['page_size']);

            //Call the function( ex: GetProduct) to the specific model class -- used callable function to make this happen
            $info = call_user_func($method, $filters);
            return $info;
        }
    }

    //! INPUT VALIDATION - IF STRING
    /**
     * Validates a string to ensure that it only contains aphabetic and spaces
     * @param array $filters
     * @param string $arrayCheck
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @throws \App\Exceptions\HttpInvalidInputException
     * @return void
     */
    public function validateString(array $filters, string $arrayCheck, Request $request)
    {

        //* Validation
        // Validate filters with regex - only letters and space allowed
        $rules = array(

            'filter' => [
                ['regex', '/^[A-Za-z ]+$/']
            ]
        );

        if (isset($filters[$arrayCheck])) {

            $filter_data = ['filter' => $filters[$arrayCheck]];

            // Using Valitron
            $validator = new Validator($filter_data, [], 'en');
            // Map rules to data
            $validator->mapFieldsRules($rules);

            // Throw exception if not valid
            if (!$validator->validate()) {

                throw new HttpInvalidInputException($request, "Invalid input. Special charaters and number are not valid.");
            }
        }
    }

    //! FILTER INPUT VALIDATION - REGEX FOR IDs
    /**
     * Validates a filter input using a regex
     *
     * @param array $filters
     * @param string $regex_id
     * @param string $column
     * @param string $errorMessage
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @throws \App\Exceptions\HttpInvalidInputException
     * @return void
     */
    public function validateFilterIds(array $filters, string $regex_id, string $column, string $errorMessage, Request $request)
    {
        //* Validation
        // Validate ids
        $rules = array(
            'filter' => [
                ['regex', $regex_id]
            ]
        );


        // Check if filter is not empty
        if (isset($filters[$column])) {

            //    $filter = $filters[$column];

            $filter_data = ['filter' => $filters[$column]];
            // Using Valitron
            $validator = new Validator($filter_data, [], 'en');
            // Map rules to data
            $validator->mapFieldsRules($rules);

            // Throw exception if not valid
            if (!$validator->validate()) {

                throw new HttpInvalidInputException($request, $errorMessage);
            }
            // //$regex_tour_id = '/^WC-\d{4}$/'; regex id
            // if (preg_match($regex_id, $filter) === 0) {
            //     throw new HttpInvalidInputException($request, $errorMessage);
            // }
        }
    }
}
