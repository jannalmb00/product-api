<?php

namespace App\Controllers;

use App\Exceptions\HttpNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Exceptions\HttpNoContentException;
use App\Exceptions\HttpInvalidInputException;
use App\Models\CompositeModel;
use App\Core\AppSettings;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use App\Validation\Validator;
use App\Models\AllergensModel;

class CompositeController extends BaseController
{

    private AllergensModel $allergens_model;
    private Client $http_client;


    public function __construct(protected AppSettings $appSettings, private CompositeModel $composite_model, AllergensModel $allergens_model) // replace the reference to a service, and the service will have a reference to the model
    {
        //To initialize the validator
        parent::__construct();

        // For Fruit composite resource
        $this->allergens_model = $allergens_model;
        // Guzzle client
        $this->http_client = new Client();
    }


    public function handleGetCoffeeCategory(Request $request, Response $response): Response
    {
        //*Filters
        $filters = $request->getQueryParams();

        // //? Validation & exception handling of filter parameters

        //!NOTE: Can't add a Name filter for product that filters if the product name is only letters (ex: 2% milk)  -- need ideas
        // You can, use the valitron -> ascii

        //* Validating if input are string


        //* paginate -- function from base controller
        $info = $this->pagination($filters, $this->composite_model, [$this->composite_model, 'getProducts'], $request);


        if ($info["data"] == false) {
            //! no matching record in the db
            throw new HttpNoContentException($request, "Request successful. No product in the record.");
        }

        return $this->renderJson($response, $info);
    }

    public function handleGetFruitInformation(Request $request, Response $response, array $uri_args): Response
    {

        if (!isset($uri_args['fruit_name'])) {
            throw new HttpInvalidInputException($request, "Fruit name is required in the URI");
        }

        $name = $uri_args['fruit_name'];

        //? Validation & exception handling of filter parameters
        //* Validating if input are string

        $rules = array(
            'fruit_name' => [
                'required',
                'alpha',
                array('lengthMin', 2)
            ]
        );

        $validateName = $uri_args;

        $validator = new Validator($validateName, [], 'en');
        $validator->mapFieldsRules($rules);

        //  throw exception if there's error in validation
        if (!$validator->validate()) {

            throw new HttpInvalidInputException($request, $validator->errorsToString());
        }


        try {
            //* Call Fruits API
            $api_request = $this->http_client->request('GET', "https://www.fruityvice.com/api/fruit/{$name}");

            // Fetch the fruit from the composite resource (Fruit API)
            $api_content = $api_request->getBody()->getContents();

            // fruit info from composite resource
            $fruit_data = json_decode($api_content, true);
        } catch (GuzzleException $e) {
            throw new HttpNotFoundException($request, "Error fetching fruits: " . $e->getMessage());
        }

        // Checks if fruit data exists --
        if (!isset($fruit_data["name"])) {
            throw new HttpNotFoundException($request, "No information found for fruit: {$name}");
        }

        $filter = ["food_item" => $name];


        // Fetch the allergen of a fruit from the db
        $allergen_data["allergen"] =
            $this->allergens_model->getAllergens($filter);

        $allergen_info = $allergen_data["allergen"];

        if (empty($allergen_info["data"])) {
            throw new HttpNotFoundException($request, "No allergen found for that fruit in the database");
        }

        // extracted allergen data, without pagination
        //* Prepare response
        $result = [
            'allergen' => $allergen_info["data"],
            'fruit' => $fruit_data
        ];


        return $this->renderJson($response, $result);
    }
}
