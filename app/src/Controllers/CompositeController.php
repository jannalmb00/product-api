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
use App\Models\CategoriesModel;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;



class CompositeController extends BaseController
{

    private CategoriesModel $categories_model;
    private Client $http_client;

    public function __construct(CategoriesModel $categories_model) // replace the reference to a service, and the service will have a reference to the model
    {
        //To initialize the validator
        parent::__construct();
        $this->categories_model = $categories_model;
        $this->http_client = new Client();
    }

    public function handleGetCocktailsCategories(Request $request, Response $response, array $uri_args): Response
    {

        //*Filters
        $filters = $request->getQueryParams();
        //dd($filters);


        $name = $filters['name'] ?? '';

        if (empty($name)) {
            throw new HttpBadRequestException($request, "Should input name of the an alcohol");
        }


        try {
            $api_response = $this->http_client->request('GET', 'https://www.thecocktaildb.com/api/json/v1/1/search.php', ['query' => ['s' => $name]]);

            $content = json_decode($api_response->getBody()->getContents(), true);
            //dd($content);

            $drinks = $content['drinks'] ?? [];

            if (empty($drinks)) {
                throw new HttpNoContentException($request, "No cocktail found in The CocktailDB for " . $name . "");
            }

            $result = [];


            foreach ($drinks as $drink) {
                $strCategory = $drink['strCategory'] ?? null;

                if ($strCategory) {
                    $filters['category_name'] = $strCategory;
                    $categories_info = $this->pagination($filters, $this->categories_model, [$this->categories_model, 'getCategories'], $request);

                    if (!empty($categories_info['data'])) {
                        $category = $categories_info['data'][0];
                    } else {
                        $category = null;
                    }
                }

                $result[] = [
                    'idDrink' => $drink['idDrink'],
                    'strDrink' => $drink['strDrink'],
                    'strTags' => $drink['strTags'],
                    'strCategory' => $strCategory,
                    'categoryDetails' => $category,
                    'strInstructions' => $drink['strInstructions'],
                    'strDrinkThumb' => $drink['strDrinkThumb'],
                ];
            }

            return $this->renderJson($response, $result);
        } catch (GuzzleException $e) {
            throw new Exception("Failed to fetchh data from The CocktailDB", $e->getMessage());
        }
    }
}
