<?php

declare(strict_types=1);

use App\Controllers\AboutController;
use App\Controllers\AccountController;
use App\Controllers\ProductsController;
use App\Controllers\CategoriesController;
use App\Controllers\AllergensController;
use App\Controllers\UserController;
use App\Helpers\DateTimeHelper;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

return static function (Slim\App $app): void {
    // Routes with authentication


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

    //* ROUTE: POST /products
    $app->post('/products', [ProductsController::class, 'handleCreateProducts']);

    //* ROUTE: PUT /products
    $app->put('/products/{product_id}', [ProductsController::class, 'handleUpdateProduct']);

    //* ROUTE: DELETE /products
    $app->delete('/products', [ProductsController::class, 'handleDeleteProduct']);

    //?---------CATEGORIES----------------------------------------------
    //!
    //* ROUTE: GET /categories
    $app->get('/categories', [CategoriesController::class, 'handleGetCategories']);

    //!
    //* ROUTE: GET /categories/{category_id}
    $app->get('/categories/{category_id}', [CategoriesController::class, 'handleGetCategoryById']);

    //!
    //* ROUTE: GET /categories/{category_id}/brands
    $app->get('/categories/{category_id}/brands', [CategoriesController::class, 'handleGetBrandsByCategory']);

    //* ROUTE: POST /categories
    $app->post('/categories', [CategoriesController::class, 'handleCreateCategories']);

    //* ROUTE: PUT /categories/{category_id}
    $app->put('/categories/{category_id}', [CategoriesController::class, 'handleUpdateCategories']);

    //* ROUTE: DELETE /categories
    $app->DELETE('/categories', [CategoriesController::class, 'handleDeleteCategories']);



    //?---------ALLERGENS----------------------------------------------
    //!
    //* ROUTE: GET /allergens
    $app->get('/allergens', [AllergensController::class, 'handleGetAllergens']);

    //!
    //* ROUTE: GET /allergens/{allergens_id}
    $app->get('/allergens/{allergen_id}', [AllergensController::class, 'handleGetAllergenById']);

    //!
    //* ROUTE: GET /allergens/{allergens_id}/ingredients
    $app->get('/allergens/{allergen_id}/ingredients', [AllergensController::class, 'handleGetIngredientsByAllergen']);

    //* ROUTE: POST /allergens
    $app->post('/allergens', [AllergensController::class, 'handleCreateAllergens']);

    //* ROUTE: PUT /allergens
    $app->put('/allergens/{allergen_id}', [AllergensController::class, 'handleUpdateAllergen']);

    //* ROUTE: DELETE /allergens
    $app->delete('/allergens', [AllergensController::class, 'handleDeleteAllergen']);


    //?---------User----------------------------------------------

    //* ROUTE: POST /register
    $app->post('/register', [UserController::class, 'handleCreateRegister']);
    //* ROUTE: POST /login
    $app->post("/login", [UserController::class, 'handleUserLogin']);

    //* ROUTE: GET /ping
    $app->get('/ping', function (Request $request, Response $response, $args) {
        $payload = [
            "greetings" => "Reporting! Hello there!",
            "now" => DateTimeHelper::now(DateTimeHelper::Y_M_D_H_M),
        ];
        $response->getBody()->write(json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR));
        return $response;
    });

    // Add proxy


    // Example route to test error handling
    $app->get('/error', function (Request $request, Response $response, $args) {
        throw new \Slim\Exception\HttpNotFoundException($request, "Something went wrong");
    });
};
