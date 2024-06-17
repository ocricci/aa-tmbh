<?php

use DI\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use App\Controllers\AssociationController;
use App\Controllers\SupplierController;
use App\Controllers\BootController;
use App\Controllers\ProductController;
use App\Middlewares\UniqueNullableMiddleware;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$container = new Container();

AppFactory::setContainer($container);

$app = AppFactory::create();

// middlewares
$app->add(new UniqueNullableMiddleware());
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(false, true, true);

// routes
$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Timberhub!");
    return $response;
});

$container = $app->getContainer();

$container->set(BootController::class, function (ContainerInterface $container) {
    return new BootController();
});

$container->set(SupplierController::class, function (ContainerInterface $container) {
    return new SupplierController(
        new \App\Actions\SupplierAction(
            new \App\Repositories\SupplierRepository()
        )
    );
});

$container->set(ProductController::class, function (ContainerInterface $container) {
    return new ProductController(
        new \App\Actions\ProductAction(
            new \App\Repositories\ProductRepository()
        )
    );
});

$container->set(AssociationController::class, function (ContainerInterface $container) {
    return new AssociationController(
        new \App\Actions\AssociationAction(
            new \App\Repositories\AssociationRepository(),
            new \App\Repositories\SupplierRepository(),
            new \App\Repositories\ProductRepository()
        )
    );
});

// migrate and seed the SQLite3 database
$app->get('/boot', [BootController::class, 'boot']);

$app->get('/suppliers', [SupplierController::class, 'getAll']);
$app->get('/suppliers/{id}', [SupplierController::class, 'getById']);

$app->get('/products', [ProductController::class, 'getAll']);
$app->get('/products/{id}', [ProductController::class, 'getById']);
$app->post('/products', [ProductController::class, 'create']);

$app->get('/suppliers/{sid}/products', [AssociationController::class, 'getAll']);
$app->get('/suppliers/{sid}/products/{pid}', [AssociationController::class, 'getById']);
$app->post('/suppliers/{sid}/products/{pid}', [AssociationController::class, 'associate']);

$app->run();
