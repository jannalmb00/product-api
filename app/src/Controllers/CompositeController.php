<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use App\Exceptions\HttpNoContentException;
use App\Exceptions\HttpInvalidInputException;
use App\Validation\ValidationHelper;
use App\Models\CompositeModel;
use App\Models\BaseModel;
use App\Services\UserService;
use App\Core\AppSettings;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;



class CompositeController extends BaseController
{

    public function __construct(protected AppSettings $appSettings, private CompositeModel $composite_model) // replace the reference to a service, and the service will have a reference to the model
    {
        //To initialize the validator
        parent::__construct();
    }

    public function handleGetCoffeeCategory(Request $request, Response $response): Response
    {
        //*Filters
        $filters = $request->getQueryParams();

        // //? Validation & exception handling of filter parameters

        //!NOTE: Can't add a Name filter for product that filters if the product name is only letters (ex: 2% milk)  -- need ideas

        //* Validating if input are string


        //* paginate -- function from base controller
        $info = $this->pagination($filters, $this->composite_model, [$this->composite_model, 'getProducts'], $request);

        if ($info["data"] == false) {
            //! no matching record in the db
            throw new HttpNoContentException($request, "Request successful. No product in the record.");
        }

        return $this->renderJson($response, $info);
    }
}
