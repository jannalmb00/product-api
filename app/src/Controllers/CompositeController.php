<?php

namespace App\Controllers;

use App\Exceptions\HttpNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Exceptions\HttpNoContentException;
use App\Exceptions\HttpInvalidInputException;
use App\Models\CategoriesModel;
use App\Models\ProductsModel;
use App\Core\AppSettings;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use App\Validation\Validator;
use App\Models\AllergensModel;
use Slim\Exception\HttpBadRequestException;

/**
 * Controller that is for handling composite resources and aggregating them with our own resources
 */
class CompositeController extends BaseController
{

    private AllergensModel $allergens_model;
    private CategoriesModel $categories_model;
    private ProductsModel $products_model;
    private Client $http_client;


    public function __construct(protected AppSettings $appSettings, CategoriesModel $categories_model, AllergensModel $allergens_model, ProductsModel $products_model) // replace the reference to a service, and the service will have a reference to the model
    {
        //To initialize the validator
        parent::__construct();

        // For Fruit composite resource
        $this->allergens_model = $allergens_model;
        // For cocktail composite resource
        $this->categories_model = $categories_model;
        $this->products_model = $products_model;
        $this->http_client = new Client();
    }

    /**
     * Fetches cocktail data from TheCocktailDB API and matches it with the a category from the database. Gives an aggregated data of the cocktail data and category data.
     * Requires a 'name' query parameter to search for a cocktail.
     * @param \Psr\Http\Message\ServerRequestInterface $request The HTTP request.
     * @param \Psr\Http\Message\ResponseInterface $response  The HTTP response.
     * @throws \Slim\Exception\HttpBadRequestException If 'name' is missing or external API fails.
     * @throws \App\Exceptions\HttpNoContentException If no matching cocktail is found.
     * @return Response A JSON response containing cocktail and matched category data.
     */
    public function handleGetCocktailsCategories(Request $request, Response $response): Response
    {

        //*Filters
        $filters = $request->getQueryParams();

        // //? Validation & exception handling of filter parameters
        $name = $filters['name'] ?? '';

        if (empty($name)) {
            throw new HttpBadRequestException($request, "Should input name of the an alcohol");
        }


        try {
            $api_response = $this->http_client->request('GET', 'https://www.thecocktaildb.com/api/json/v1/1/search.php', ['query' => ['s' => $name]]);

            $content = json_decode($api_response->getBody()->getContents(), true);

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
            throw new HttpBadRequestException($request, "Failed to fetch data from The CocktailDB" . $e->getMessage());
        }
    }

    /**
     * Retrieves fruit information data from Fruityvice API and allergen data from the database.
     *Requires a 'fruit_name' query parameter to search for a fruit.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The HTTP request.
     * @param \Psr\Http\Message\ResponseInterface $response The HTTP response.
     * @param array $uri_args URI parameters, must include 'fruit_name'.
     * @throws \App\Exceptions\HttpInvalidInputException If 'fruit_name' is missing or invalid.
     * @throws \Slim\Exception\HttpBadRequestException If the external API request fails.
     * @throws \App\Exceptions\HttpNotFoundException If fruit data or  allergens data are not found.
     * @return Response A JSON response containing fruit info and allergen info.
     */
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
            throw new HttpBadRequestException($request, "Error fetching fruits: " . $e->getMessage());
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


    /**
     * GET: Handles recipe recommendations based on a product ID
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The request object
     * @param \Psr\Http\Message\ResponseInterface $response The response object
     * @param array $uri_args The URI arguments containing product ID
     *
     * @throws \App\Exceptions\HttpInvalidInputException When product ID is invalid
     * @throws \App\Exceptions\HttpNoContentException When no recipes are found
     * @return Response Response containing recipes and related products
     */
    public function handleGetRecipesByProduct(Request $request, Response $response, array $uri_args): Response
    {
        if (!isset($uri_args['product_id'])) {
            throw new HttpInvalidInputException($request, "Product ID is required in the URI");
        }

        $product_id = $uri_args['product_id'];

        //* Get product details from our DB
        $filters = $request->getQueryParams();
        //dd($filters);
        $filters['id'] = $product_id;

        $regex_id = '/^P\d{5,6}$/';
        $this->validateFilterIds($filters, $regex_id, 'id', "Provided product ID is invalid.", $request);

        $product_info = $this->pagination($filters, $this->products_model, [$this->products_model, 'getProductById'], $request);

        if (empty($product_info['data'])) {
            $payload = [
                "status" => "failed",
                "message" => "Can not find product info"
            ];
            return $this->renderJson($response, $payload, 404);
            // throw new HttpNoContentException($request, "Product not found");
        }

        // Gets the first ingredient
        $product = $product_info['data'][0];
        $ingredient = $this->getIngredientFromProduct($product); // Get the ingredient from product name

        try {
            //* Call TheMealDB API
            $api_response = $this->http_client->request('GET', 'https://www.themealdb.com/api/json/v1/1/filter.php', ['query' => ['i' => $ingredient]]);

            $content = $api_response->getBody()->getContents();
            $meals = json_decode($content, true); // the actual meals

            //* Get details for the first 3 meals
            $recipes = [];
            $count = 0;

            if (!empty($meals['meals'] && is_array($meals['meals']))) {

                foreach ($meals['meals'] as $meal) {
                    if ($count >= 3) {
                        break;
                    }

                    $meal_id = $meal['idMeal'];
                    $meal_details = $this->getMealDetails($meal_id);

                    if ($meal_details) {
                        $recipes[] = $meal_details;
                        $count++;
                    }
                }
            } else {
                $payload = [
                    "status" => "failed",
                    "message" => "No recipes found using the product ingredient"
                ];
                return $this->renderJson($response, $payload, 404);
            }

            //* Prepare response
            $result = [
                'product' => $product,
                'ingredient_used' => $ingredient,
                'recipes' => $recipes,
            ];

            return $this->renderJson($response, $result);
        } catch (GuzzleException $e) {
            throw new HttpNoContentException($request, "Error fetching recipes: " . $e->getMessage());
        }
    }

    /**
     * GET: Helper method that gets detailed information for a specific meal from TheMealDB based on the meal ID
     *
     * @param string $meal_id The meal ID to retrieve
     * @return array|null The meal details or null if not found
     */
    private function getMealDetails(string $meal_id): ?array
    {
        $response = $this->http_client->request('GET', 'https://www.themealdb.com/api/json/v1/1/lookup.php', ['query' => ['i' => $meal_id]]);
        $content = $response->getBody()->getContents();
        $data = json_decode($content, true);

        if (isset($data['meals']) && is_array($data['meals']) && !empty($data['meals'])) {
            return $data['meals'][0];
        }

        return null;
    }

    /**
     * Get the product ingredient from product name for recipe search
     *
     * @param array $product The product details
     * @return string The name that matches the common ingredients
     */
    private function getIngredientFromProduct(array $product): string
    {
        // Get the product name by separating the product name as per the ingredients
        $product_words = explode(' ', strtolower($product['product_name']));

        $common_ingredients = [
            // If the word does not match this it will just return the full product name
            'chicken',
            'beef',
            'pork',
            'fish',
            'milk',
            'cheese',
            'chocolate',
            'pasta',
            'rice',
            'potato'
        ];

        // If the word in the ingredient is found it returns the product name to be shared to get the recipes
        foreach ($product_words as $word) {
            if (in_array($word, $common_ingredients)) {
                return $word;
            }
        }

        return $product['product_name'];
    }
}
