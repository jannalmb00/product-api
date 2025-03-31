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
                    'filters_supported' => "N/A"
                ],
                [
                    'resource_number' => 3,
                    'uri' => '/products/{product_id}/nutrition',
                    'description' => "Gets nutrition information for the specified product",
                    'filters_supported' => "N/A"
                ],
                [
                    'resource_number' => 4,
                    'uri' => '/allergens',
                    'description' => "Gets a list of allergens matching the specified filters",
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
                    'resource_number' => 5,
                    'uri' => '/allergens/{allergen_id}',
                    'description' => "Gets details of a specific allergen",
                    'filters_supported' => "N/A"
                ],
                [
                    'resource_number' => 6,
                    'uri' => '/allergens/{allergen_id}/ingredients',
                    'description' => "Gets a list of ingredients associated with the specified allergen",
                    'filters_supported' => ['ingredient_name', 'processing_type', 'isGMO']
                ],
                [
                    'resource_number' => 7,
                    'uri' => '/categories',
                    'description' => "Gets a list of categories matching the specified filters",
                    'filters_supported' => ['category_name', 'category_type', 'parent_category'],
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
                    'resource_number' => 8,
                    'uri' => '/categories/{category_id}',
                    'description' => "Gets details of a specific category",
                    'filters_supported' => "N/A"
                ],
                [
                    'resource_number' => 9,
                    'uri' => '/categories/{category_id}/brands',
                    'description' => "Gets a list of brands associated with the specified category",
                    'filters_supported' => ['brand_name', 'brand_country']
                ]
            ]
        );

        return $this->renderJson($response, $data);
    }
}
