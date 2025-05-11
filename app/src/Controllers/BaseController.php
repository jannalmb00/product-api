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

abstract class BaseController
{
    private ValidationHelper $validator;

    public function __construct()
    {
        //Validator Helper
        $this->validator = new ValidationHelper();
    }
    protected function renderJson(Response $response, array $data, int $status_code = 200): Response
    {
        // var_dump($data);
        $payload = json_encode($data, JSON_UNESCAPED_SLASHES |    JSON_PARTIAL_OUTPUT_ON_ERROR);
        //-- Write JSON data into the response's body.
        $response->getBody()->write($payload);
        return $response->withStatus($status_code)->withAddedHeader(HEADERS_CONTENT_TYPE, APP_MEDIA_TYPE_JSON);
    }

    /**
     * Combined
     * $this->player_model->setPaginationOptions($filters["page"], $filters["page_size"
     */

    //! PAGINATION
    public function pagination(array $filters, BaseModel $model, callable $method,  Request $request): mixed
    {

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
        $validator = new Validator($page_data, [], 'en');
        // dd($page_data);
        $validator->mapFieldsRules($rules);


        if (!$validator->validate()) {

            throw new HttpInvalidInputException($request, "Invalid input. Page and page size must be an integer greater than or equal to 1.");
        } else {

            // setting pagination
            $model->setPaginationOptions((int) $page_data['page'], (int) $page_data['page_size']);

            //Call the function( ex: GetProduct) to the specific model class -- used callable function to make this happen
            $info = call_user_func($method, $filters);
            return $info;
        }
    }

    //! INPUT VALIDATION - IF STRING
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
