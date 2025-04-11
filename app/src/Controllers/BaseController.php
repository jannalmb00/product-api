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
    public function pagination(array $filters, BaseModel $model, callable $method): mixed
    {

        // validate_pages =  new Validator($filters,  );
        //Add a default if no page and page size set
        $page = isset($filters["page"]) ? (int) $filters["page"] : 1;
        $size = isset($filters["page_size"]) ? (int) $filters["page_size"] : 10;

        // setting pagination
        $model->setPaginationOptions($page, $size);

        //Call the function( ex: GetProduct) to the specific model class -- used callable function to make this happen
        $info = call_user_func($method, $filters);


        return $info;
    }

    //! INPUT VALIDATION - IF STRING
    public function validateString(array $filters, string $arrayCheck, Request $request)
    {
        //  dd($filters);
        //  dd($arrayCheck); // <pre>string(13) "chocolatelll9"</pre>

        if (isset($filters[$arrayCheck])) {

            //dd($arrayCheck); // 9
            $filter = $filters[$arrayCheck];
            //dd($filter);

            //! VALIDATION
            //Checks if input/string has numbers
            $string_valid = $this->validator->isAlpha($filter);

            if (!$string_valid) {
                throw new HttpInvalidInputException($request, "Invalid input. Special charaters and number are not valid");
            }
        }
    }

    //! FILTER INPUT VALIDATION - REGEX FOR IDs
    public function validateFilterIds(array $filters, string $regex_id, string $column, string $errorMessage, Request $request)
    {
        if (isset($filters[$column])) {
            $filter = $filters[$column];
            //$regex_tour_id = '/^WC-\d{4}$/'; regex id
            if (preg_match($regex_id, $filter) === 0) {
                throw new HttpInvalidInputException($request, $errorMessage);
            }
        }
    }

    // TODO: VALIDATE PAGE PARAMS ()
}
