<?php

declare(strict_types=1);

use App\Controllers\AboutController;
use App\Controllers\AccountController;
use App\Controllers\CalculatorController;
use App\Controllers\ProductsController;
use App\Controllers\CategoriesController;
use App\Controllers\AllergensController;
use App\Controllers\UserController;
use App\Helpers\DateTimeHelper;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Middleware\AuthMiddleware;
use App\Middleware\AdminMiddleware as AdminMiddleware;
use Slim\Routing\RouteCollectorProxy;

return static function (Slim\App $app): void {

    //? --------- PUBLIC ROUTES ------

    //?---------User----------------------------------------------

    //* ROUTE: POST /register
    $app->post('/register', [UserController::class, 'handleCreateRegister']);

    //* ROUTE: POST /login
    $app->post("/login", [UserController::class, 'handleUserLogin']);


    //* ROUTE: GET /
    $app->get('/', [AboutController::class, 'handleAboutWebService']);

    //?---Computation functionality--------
    $app->post('/calorie', [CalculatorController::class, 'calculateCalories']);

    //? --------- PROTECTED ROUTES ------
    //! All the GET methods

    $app->group('', function (RouteCollectorProxy $group) {

        //?---------PRODUCTS----------------------------------------------
        //* ROUTE: GET /products
        $group->get('/products', [ProductsController::class, 'handleGetProducts']);

        //* ROUTE: GET /products/{product_id}
        $group->get('/products/{product_id}', [ProductsController::class, 'handleGetProductById']);

        //* ROUTE: GET /products/{product_id}/nutrition
        $group->get('/products/{product_id}/nutrition', [ProductsController::class, 'handleGetProductNutrition']);


        //?---------CATEGORIES----------------------------------------------
        //* ROUTE: GET /categories
        $group->get('/categories', [CategoriesController::class, 'handleGetCategories']);

        //* ROUTE: GET /categories/{category_id}
        $group->get('/categories/{category_id}', [CategoriesController::class, 'handleGetCategoryById']);

        //* ROUTE: GET /categories/{category_id}/brands
        $group->get('/categories/{category_id}/brands', [CategoriesController::class, 'handleGetBrandsByCategory']);


        //?---------ALLERGENS----------------------------------------------
        //* ROUTE: GET /allergens
        $group->get('/allergens', [AllergensController::class, 'handleGetAllergens']);

        //* ROUTE: GET /allergens/{allergens_id}
        $group->get('/allergens/{allergen_id}', [AllergensController::class, 'handleGetAllergenById']);

        //* ROUTE: GET /allergens/{allergens_id}/ingredients
        $group->get('/allergens/{allergen_id}/ingredients', [AllergensController::class, 'handleGetIngredientsByAllergen']);
    })->add($app->getContainer()->get(AuthMiddleware::class));

    $app->group('/admin', function (RouteCollectorProxy $group) {
        $group->post('/users', [UserController::class, 'createUser']);

        //?---------PRODUCTS----------------------------------------------
        //* ROUTE: POST /products
        $group->post('/products', [ProductsController::class, 'handleCreateProducts']);

        //* ROUTE: PUT /products
        $group->put('/products/{product_id}', [ProductsController::class, 'handleUpdateProduct']);

        //* ROUTE: DELETE /products
        $group->delete('/products', [ProductsController::class, 'handleDeleteProduct']);


        //?---------CATEGORIES----------------------------------------------
        //* ROUTE: POST /categories
        $group->post('/categories', [CategoriesController::class, 'handleCreateCategories']);

        //* ROUTE: PUT /categories/{category_id}
        $group->put('/categories/{category_id}', [CategoriesController::class, 'handleUpdateCategories']);

        //* ROUTE: DELETE /categories
        $group->DELETE('/categories', [CategoriesController::class, 'handleDeleteCategories']);

        //?---------ALLERGENS----------------------------------------------
        //* ROUTE: POST /allergens
        $group->post('/allergens', [AllergensController::class, 'handleCreateAllergens']);

        //* ROUTE: PUT /allergens
        $group->put('/allergens/{allergen_id}', [AllergensController::class, 'handleUpdateAllergen']);

        //* ROUTE: DELETE /allergens
        $group->delete('/allergens', [AllergensController::class, 'handleDeleteAllergen']);
    })->add(AdminMiddleware::class)
        ->add($app->getContainer()->get(AuthMiddleware::class));

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
    $app->get('/explode', function () {
        throw new \RuntimeException("Boom!");
    });
};
