<?php

use DI\Container;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use App\Controllers\SupplierController;
use App\Controllers\BootController;
use App\Controllers\ProductController;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$container = new Container();

AppFactory::setContainer($container);

$app = AppFactory::create();

/**
 * Middleware to handle null or empty value for treatment
 * SQLite3 loses the unique key when using NULL
 */
$app->add(function (Request $request, RequestHandler $handler) {
    if ($request->getMethod() == "POST" && str_contains((string) $request->getUri(), "products")) {
        $parsedBody = $request->getParsedBody();
        if (empty($parsedBody['treatment'])) {
            $parsedBody['treatment'] = 'null';
            $request = $request->withParsedBody($parsedBody);
        }
    }

    return $handler->handle($request);
});

$app->addBodyParsingMiddleware();

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

// migrate and seed the SQLite3 database
$app->get('/boot', [BootController::class, 'boot']);

$app->get('/suppliers', [SupplierController::class, 'getAll']);
$app->get('/suppliers/{id}', [SupplierController::class, 'getById']);

$app->get('/products', [ProductController::class, 'getAll']);
$app->get('/products/{id}', [ProductController::class, 'getById']);
$app->post('/products', [ProductController::class, 'create']);

$app->run();
