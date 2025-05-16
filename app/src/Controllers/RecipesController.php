<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Exceptions\HttpInvalidInputException;
use App\Exceptions\HttpNoContentException;
use App\Models\ProductsModel;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Controller that is for handling recipe recommendations based on food products
 */
class RecipesController extends BaseController
{
    private ProductsModel $products_model;
    private Client $http_client;

    /**
     * RecipeController constructor
     *
     * @param \App\Models\ProductsModel $products_model The products model
     */
    public function __construct(ProductsModel $products_model)
    {
        parent::__construct();
        $this->products_model = $products_model;
        $this->http_client = new Client();
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

        // If the body product is empty or not found
        if (empty($product_info['data'])) {
            throw new HttpNoContentException($request, "Product not found");
        }

        // Gets the first ingredient
        $product = $product_info['data'][0];
        $ingredient = $this->getIngredientFromProduct($product); // Get the ingredient from product name

        try {
            //* Call TheMealDB API
            $api_response = $this->http_client->request('GET', 'https://www.themealdb.com/api/json/v1/1/filter.php', ['query' => ['i' => $ingredient]]);

            $content = $api_response->getBody()->getContents();
            $meals = json_decode($content, true);

            //* Get details for the first 3 meals
            $recipes = [];
            $count = 0;

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
     * Get detailed information for a specific meal from TheMealDB
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
        // For this time, we use this method as we do not have product_ingredients data for now, we use based off product name!
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
