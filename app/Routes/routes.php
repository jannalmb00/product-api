<?php

declare(strict_types=1);

use App\Controllers\AboutController;
use App\Controllers\ProductsController;
use App\Controllers\CategoriesController;
use App\Controllers\AllergensController;
use App\Helpers\DateTimeHelper;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

return static function (Slim\App $app): void {
    // Routes with authentication

    //* ROUTE: POST /
    $app->post('/products', [ProductsController::class, 'handleCreateProducts']);

    //* ROUTE: GET /
    $app->get('/', [AboutController::class, 'handleAboutWebService']);

    //?---------PRODUCTS----------------------------------------------
    //* ROUTE: GET /products
    $app->get('/products', [ProductsController::class, 'handleGetProducts']);

    //!
    //* ROUTE: GET /products/{product_id}
    $app->get('/products/{product_id}', [ProductsController::class, 'handleGetProductById']);

    //!
    //* ROUTE: GET /products/{product_id}/nutrition
    $app->get('/products/{product_id}/nutrition', [ProductsController::class, 'handleGetProductNutrition']);

    //?---------CATEGORIES----------------------------------------------
    //!
    //* ROUTE: GET /categories
    $app->get('/categories', [CategoriesController::class, 'handleGetCategories']);

    //!
    //* ROUTE: GET /categories/{categories_id}
    $app->get('/categories/{category_id}', [CategoriesController::class, 'handleGetCategoryById']);

    //!
    //* ROUTE: GET /categories/{categories_id}/brands
    $app->get('/categories/{category_id}/brands', [CategoriesController::class, 'handleGetBrandsByCategory']);

    //* ROUTE: POST /
    $app->post('/categories', [CategoriesController::class, 'handleCreateCategories']);

    //* ROUTE: PUT /
    $app->put('/categories', [CategoriesController::class, 'handleUpdateCategories']);

    //* ROUTE: DELETE /
    $app->DELETE('/categories', [CategoriesController::class, 'handleDeleteCategories']);



    //?---------ALLERGENS----------------------------------------------
    //!
    //* ROUTE: GET /allergens
    $app->get('/allergens', [AllergensController::class, 'handleGetAllergens']);

    //!
    //* ROUTE: GET /allergens/{allergens_id}
    $app->get('/allergens/{allergen_id}', [AllergensController::class, 'handleGetAllergenById']);

    //!
    //* ROUTE: GET /products/{product_id}/ingredients
    $app->get('/allergens/{allergens_id}/ingredients', [AllergensController::class, 'handleGetIngredientsByAllergen']);

    //* ROUTE: POST /allergens
    $app->post('/allergens', [AllergensController::class, 'handleCreateAllergens']);

    //* ROUTE: PUT /allergens
    $app->put('/allergens/{allergen_id}', [AllergensController::class, 'handleUpdateAllergenById']);

    //* ROUTE: DELETE /allergens
    $app->delete('/allergens', [AllergensController::class, 'handleDeleteAllergenById']);

    // Validation Helper
    // $app->get('/test', [TestController::class, 'handleTest']);
    //* ROUTE: GET /ping
    $app->get('/ping', function (Request $request, Response $response, $args) {
        $payload = [
            "greetings" => "Reporting! Hello there!",
            "now" => DateTimeHelper::now(DateTimeHelper::Y_M_D_H_M),
        ];
        $response->getBody()->write(json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR));
        return $response;
    });
    // Example route to test error handling
    $app->get('/error', function (Request $request, Response $response, $args) {
        throw new \Slim\Exception\HttpNotFoundException($request, "Something went wrong");
    });
};
