<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\AppSettings;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AboutController extends BaseController
{
    private const API_NAME = 'FOOD-API';

    private const API_VERSION = '1.0.0';

    public function handleAboutWebService(Request $request, Response $response): Response
    {
        $data = array(
            'api' => self::API_NAME,
            'version' => self::API_VERSION,
            'about' => 'Welcome to our Product API where we introduce information about food products specifically. ',
            'authors' => 'Grechelle Uy, Janna Lomibao, Bridjette Nania Centro',
            'pagination' => 'Pagination is true for all resources. With 5 result sets at a time by default as it is overrided.',
            'sorting' => 'Sorting is true for all resources. Ascending by default with sorting options such as ASC and DESC.',
            'resources' => [
                [
                    'resource_number' => 1,
                    'uri' => '/products',
                    'description' => "Lists of zero or more products that match the product's criteria.",
                    'methods' => ['GET', 'POST', 'DELETE'],
                    'filters_supported' => ['product_name', 'product_origin', 'brand_name', 'category_name'],
                    'sorting' => [
                        [
                            'sortBy' => ['product_name', 'product_origin']
                        ],
                        [
                            'orderBy' => ['ASC', 'DESC']
                        ]
                    ]
                ],
                [
                    'resource_number' => 2,
                    'uri' => '/products/{product_id}',
                    'description' => "Details of a specific product",
                    'methods' => ['GET', 'PUT'],
                    'filters_supported' => "N/A"
                ],
                [
                    'resource_number' => 3,
                    'uri' => '/products/{product_id}/nutrition',
                    'description' => "Gets nutrition information for the specified product",
                    'methods' => ['GET'],
                    'filters_supported' => "N/A"
                ],
                [
                    'resource_number' => 4,
                    'uri' => '/brands/{brand_id}/products',
                    'description' => 'Lists products under a specific brand.',
                    'methods' => ['GET'],
                    'filters_supported' => ['product_name', 'category_name']
                ],

                [
                    'resource_number' => 5,
                    'uri' => '/allergens',
                    'description' => "Gets a list of allergens matching the specified filters",
                    'methods' => ['GET', 'POST', 'DELETE'],
                    'filters_supported' => ['allergen_name', 'food_group', 'food_type', 'food_origin', 'food_item'],
                    'sorting' => [
                        [
                            'sortBy' => ['allergen_name', 'allergen_reaction_type', 'food_group', 'food_origin', 'food_type']
                        ],
                        [
                            'orderBy' => ['ASC', 'DESC']
                        ]
                    ]
                ],
                [
                    'resource_number' => 6,
                    'uri' => '/allergens/{allergen_id}',
                    'methods' => ['GET', 'PUT'],
                    'description' => "Gets details of a specific allergen",
                    'filters_supported' => "N/A"
                ],
                [
                    'resource_number' => 7,
                    'uri' => '/allergens/{allergen_id}/ingredients',
                    'description' => "Gets a list of ingredients associated with the specified allergen",
                    'filters_supported' => ['ingredient_name', 'processing_type', 'isGMO']
                ],
                [
                    'resource_number' => 8,
                    'uri' => '/categories',
                    'description' => "Gets a list of categories matching the specified filters",
                    'filters_supported' => ['category_name', 'category_type', 'parent_category'],
                    'methods' => ['GET', 'POST', 'DELETE'],
                    'sorting' => [
                        [
                            'sortBy' => ['category_name', 'category_type', 'parent_category']
                        ],
                        [
                            'orderBy' => ['ASC', 'DESC']
                        ]
                    ]
                ],
                [
                    'resource_number' => 9,
                    'uri' => '/categories/{category_id}',
                    'description' => "Gets details of a specific category",
                    'methods' => ['GET', 'PUT'],
                    'filters_supported' => "N/A"
                ],
                [
                    'resource_number' => 10,
                    'uri' => '/categories/{category_id}/brands',
                    'description' => "Gets a list of brands associated with the specified category",
                    'methods' => ['GET'],
                    'filters_supported' => ['brand_name', 'brand_country']
                ],
                [
                    'resource_number' => 11,
                    'uri' => '/calorie',
                    'description' => 'Calculate the calorie value based on individual data.',
                    'methods' => ['POST'],
                    'filters_supported' => ['gender', 'weight', 'height', 'age', 'activity_per_week']
                ],
                [
                    'resource_number' => 12,
                    'uri' => '/fiber',
                    'description' => 'Calculate fiber intake based on consumed daily caolories data.',
                    'methods' => ['POST'],
                    'filters_supported' => ['N/A']
                ],
                [
                    'resource_number' => 13,
                    'uri' => '/bmi',
                    'description' => 'Calculate Body Mass Index using height and weight.',
                    'methods' => ['POST'],
                    'filters_supported' => ['height', 'weight']
                ],
                [
                    'resource_number' => 14,
                    'uri' => '/cocktail_category',
                    'description' => 'Returns categories of cocktails available in the system.',
                    'methods' => ['GET'],
                    'filters_supported' => ['category_name']
                ],
                [
                    'resource_number' => 15,
                    'uri' => '/fruit_information/{fruit_name}',
                    'description' => 'Fetches detailed nutritional information for a specific fruit.',
                    'methods' => ['GET'],
                    'filters_supported' => ['fruit_name']
                ],
                [
                    'resource_number' => 16,
                    'uri' => '/recipes/product/{product_id}',
                    'description' => 'Fetches recipe suggestions or data related to a product.',
                    'methods' => ['GET'],
                    'filters_supported' => ['product_id']
                ],
                [
                    'resource_number' => 17,
                    'uri' => '/register',
                    'description' => 'Registers a new user to the system.',
                    'methods' => ['POST'],
                    'filters_supported' => ['first_name', 'last_name', 'email', 'password', 'isAdmin']
                ],
                [
                    'resource_number' => 18,
                    'uri' => '/login',
                    'description' => 'Authenticates user credentials and returns a token.',
                    'methods' => ['POST'],
                    'filters_supported' => ['email', 'password']
                ],
            ]
        );

        return $this->renderJson($response, $data);
    }
}
