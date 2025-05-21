<?php

declare(strict_types=1);

use App\Controllers\AboutController;
use App\Controllers\AccountController;
use App\Controllers\ProductsController;
use App\Controllers\CategoriesController;
use App\Controllers\AllergensController;
use App\Controllers\CalculatorController;
use App\Controllers\CompositeController;
use App\Controllers\UserController;
use App\Controllers\RecipesController;
use App\Controllers\BrandController;

use App\Helpers\DateTimeHelper;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Middleware\AuthMiddleware;
use App\Middleware\AdminMiddleware as AdminMiddleware;
use Slim\Routing\RouteCollectorProxy;


return static function (Slim\App $app, array $settings): void {
    // Create middleware instances directly in routes file
    $authMiddleware = new AuthMiddleware($settings['jwt_key']);
    $adminMiddleware = new AdminMiddleware();

    //? --------------------
    //? PUBLIC ROUTES
    //? --------------------

    $app->post('/register', [UserController::class, 'handleCreateRegister']);
    $app->post('/login', [UserController::class, 'handleUserLogin']);
    $app->get('/', [AboutController::class, 'handleAboutWebService']);


    //? --------------------
    //? COMPUTATION FUNCTIONALITY
    //? --------------------
    $app->post("/calorie", [CalculatorController::class, 'handleCalculateCalories']);
    $app->post("/fiber", [CalculatorController::class, 'handleCalculateFiber']);
    $app->post("/bmi", [CalculatorController::class, 'handleCalculateBMI']);

    $app->get("/cocktail_category", [CompositeController::class, 'handleGetCocktailsCategories']);

    $app->get('/brands/{brand_id}/products', [BrandController::class, 'handleGetProductsByBrand']);

    //*ROUTE:GET /coffee-info
    //$app->$get("/coffee_category", [CompositeController::class, 'handleGetCoffeeCategory']);

    // *ROUTE:GET /fruit-info
    $app->get("/fruit_information/{fruit_name}", [CompositeController::class, 'handleGetFruitInformation']);

    //? --------- PROTECTED ROUTES ------
    //! All the GET methods

    $app->group('', function (RouteCollectorProxy $group) {

        //?---------PRODUCTS----------------------------------------------
        $group->get('/products', [ProductsController::class, 'handleGetProducts']);
        $group->get('/products/{product_id}', [ProductsController::class, 'handleGetProductById']);
        $group->get('/products/{product_id}/nutrition', [ProductsController::class, 'handleGetProductNutrition']);

        //?---------CATEGORIES----------------------------------------------
        $group->get('/categories', [CategoriesController::class, 'handleGetCategories']);
        $group->get('/categories/{category_id}', [CategoriesController::class, 'handleGetCategoryById']);
        $group->get('/categories/{category_id}/brands', [CategoriesController::class, 'handleGetBrandsByCategory']);

        //?---------ALLERGENS----------------------------------------------
        $group->get('/allergens', [AllergensController::class, 'handleGetAllergens']);
        $group->get('/allergens/{allergen_id}', [AllergensController::class, 'handleGetAllergenById']);
        $group->get('/allergens/{allergen_id}/ingredients', [AllergensController::class, 'handleGetIngredientsByAllergen']);

        //? -- Shared --
        // $group->get('/admin/products', [ProductsController::class, 'handleGetProducts']);
        // $group->get('/admin/categories', [CategoriesController::class, 'handleGetCategories']);
        // $group->get('/admin/allergens', [AllergensController::class, 'handleGetAllergens']);

        //? == Composite resource -- TheMealDBAPI
        $group->get('/recipes/product/{product_id}', [CompositeController::class, 'handleGetRecipesByProduct']);
        // $group->post('/cocktail', [CompositeController::class, 'handleGetCocktailByName']);
        /**
         * So i sesearch yung name nung cocktail then sa ingrdients use ingredient table to give more details slay!!!!!
         */
    })->add($authMiddleware);


    $app->group('', function (RouteCollectorProxy $group) {
        $group->post('/users', [UserController::class, 'createUser']);

        //?---------PRODUCTS----------------------------------------------

        $group->post('/products', [ProductsController::class, 'handleCreateProducts']);
        $group->put('/products/{product_id}', [ProductsController::class, 'handleUpdateProduct']);
        $group->delete('/products', [ProductsController::class, 'handleDeleteProduct']);

        //?---------ALLERGENS----------------------------------------------

        $group->post('/categories', [CategoriesController::class, 'handleCreateCategories']);
        $group->put('/categories/{category_id}', [CategoriesController::class, 'handleUpdateCategories']);
        $group->delete('/categories', [CategoriesController::class, 'handleDeleteCategories']);

        //?---------ALLERGENS----------------------------------------------
        $group->post('/allergens', [AllergensController::class, 'handleCreateAllergens']);
        $group->put('/allergens/{allergen_id}', [AllergensController::class, 'handleUpdateAllergen']);
        $group->delete('/allergens', [AllergensController::class, 'handleDeleteAllergen']);
    })->add($adminMiddleware)->add($authMiddleware);

    // Ping route
    $app->get('/ping', function (Request $request, Response $response) {
        $payload = [
            "greetings" => "Reporting! Hello there!",
            "now" => DateTimeHelper::now(DateTimeHelper::Y_M_D_H_M),
        ];
        $response->getBody()->write(json_encode($payload));
        return $response;
    });


    $app->get('/error', function (Request $request, Response $response, $args) {
        throw new \Slim\Exception\HttpNotFoundException($request, "Something went wrong");
    });
    $app->get('/explode', function () {
        throw new \RuntimeException("Boom!");
    });
};
