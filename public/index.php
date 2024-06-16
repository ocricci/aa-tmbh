<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Controllers\SupplierController;
use App\Controllers\BootController;
use Psr\Container\ContainerInterface;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Timberhub!");
    return $response;
});

$container = $app->getContainer();

$container[BootController::class] = function (ContainerInterface $container) {
    return new BootController();
};

$container[SupplierController::class] = function (ContainerInterface $container) {
    return new SupplierController();
};

// migrate and seed the SQLite3 database
$app->get('/boot', [BootController::class, 'boot']);

$app->get('/suppliers', [SupplierController::class, 'getAll']);
$app->get('/suppliers/{id}', function (Request $request, Response $response, $args) {
    $response->getBody()->write(json_encode(['Supplier: ' . $args['id'] ?? 'ID is missing']));
    return $response
        ->withHeader('Content-type', 'application/json')
        ->withStatus(200);
});

$app->run();
