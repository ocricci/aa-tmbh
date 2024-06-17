<?php

namespace App\Test\Container;

use Nyholm\Psr7\Factory\Psr17Factory;
use App\Controllers\AssociationController;
use App\Controllers\SupplierController;
use App\Controllers\ProductController;
use App\Middlewares\UniqueNullableMiddleware;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Interfaces\RouteParserInterface;

class Builder
{
    public static function getDefinitions(): array
    {
        return [
            App::class => function (ContainerInterface $container) {
                $app = AppFactory::createFromContainer($container);
        
                // Register routes
                $app->get('/', function (Request $request, Response $response, $args) {
                    $response->getBody()->write("Timberhub!");
                    return $response;
                });
               
                $container->set(SupplierController::class, function (ContainerInterface $container) {
                    return new SupplierController(
                        new \App\Actions\SupplierAction(
                            new \App\Test\Repositories\SupplierMemoryRepository()
                        )
                    );
                });

                $container->set(ProductController::class, function (ContainerInterface $container) {
                    return new ProductController(
                        new \App\Actions\ProductAction(
                            new \App\Test\Repositories\ProductMemoryRepository()
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

                $app->get('/suppliers', [SupplierController::class, 'getAll']);
                $app->get('/suppliers/{id}', [SupplierController::class, 'getById']);

                $app->get('/products', [ProductController::class, 'getAll']);
                $app->get('/products/{id}', [ProductController::class, 'getById']);
                $app->post('/products', [ProductController::class, 'create']);

                $app->get('/suppliers/{sid}/products', [AssociationController::class, 'getAll']);
                $app->get('/suppliers/{sid}/products/{pid}', [AssociationController::class, 'getById']);
                $app->post('/suppliers/{sid}/products/{pid}', [AssociationController::class, 'associate']);

                // Register middleware
                $app->add(new UniqueNullableMiddleware());
                $app->addBodyParsingMiddleware();
                $app->addRoutingMiddleware();
                $app->addErrorMiddleware(false, false, false);

                return $app;
            },
        
            // HTTP factories
            ResponseFactoryInterface::class => function (ContainerInterface $container) {
                return $container->get(Psr17Factory::class);
            },
        
            ServerRequestFactoryInterface::class => function (ContainerInterface $container) {
                return $container->get(Psr17Factory::class);
            },
        
            StreamFactoryInterface::class => function (ContainerInterface $container) {
                return $container->get(Psr17Factory::class);
            },
        
            UploadedFileFactoryInterface::class => function (ContainerInterface $container) {
                return $container->get(Psr17Factory::class);
            },
        
            UriFactoryInterface::class => function (ContainerInterface $container) {
                return $container->get(Psr17Factory::class);
            },
        
            // The Slim RouterParser
            RouteParserInterface::class => function (ContainerInterface $container) {
                return $container->get(App::class)->getRouteCollector()->getRouteParser();
            },
       ];
    }
}
