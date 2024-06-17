<?php

namespace App\Test\TestCase;

use PHPUnit\Framework\TestCase;
use Fig\Http\Message\StatusCodeInterface;
use App\Test\Traits\AppTestTrait;
use App\Test\Repositories\ProductMemoryRepository;

class PublicTest extends TestCase
{
    use AppTestTrait;

    public function testTimberhub()
    {
        $request = $this->createRequest('GET', '/');
        $response = $this->app->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertResponseContains('Timberhub!', $response);
    }

    public function testSupplierList()
    {
        $request = $this->createRequest('GET', '/suppliers');
        $response = $this->app->handle($request);
        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertResponseContains('id', $response);
        $this->assertResponseContains('name', $response);
    }

    public function testSupplierById()
    {
        $request = $this->createRequest('GET', '/suppliers/1');
        $response = $this->app->handle($request);
        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertJsonData(['id' => 1, 'name' => 'Memory Supplier'], $response);
    }

    public function testSupplierUnprocessableEntity()
    {
        $request = $this->createRequest('GET', '/suppliers/aaaaa');
        $response = $this->app->handle($request);
        $this->assertSame(StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }

    public function testSupplierNotFound()
    {
        $request = $this->createRequest('GET', '/suppliers/100');
        $response = $this->app->handle($request);
        $this->assertSame(StatusCodeInterface::STATUS_NOT_FOUND, $response->getStatusCode());
    }

    public function testProductList()
    {
        $request = $this->createRequest('GET', '/products');
        $response = $this->app->handle($request);
        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertJsonData((new ProductMemoryRepository())->getAll(), $response);
    }

    public function testProductById()
    {
        $request = $this->createRequest('GET', '/products/1');
        $response = $this->app->handle($request);
        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertJsonData((new ProductMemoryRepository())->getById(1), $response);
    }

    public function testProductCreate()
    {
        $mockProduct = [
            "species" => "pine",
            "grading_system" => "nordic_blue",
            "grade" => "aaa1aaa",
            "drying" => "fresh",
            "treatment" => "null",
            "thickness" => 50,
            "width" => 120,
            "length" => 1200
        ];

        $repo = new ProductMemoryRepository();
        $products = $repo->getAll();
        $id = count($products) + 1;
        $request = $this->createJsonRequest('POST', '/products', $mockProduct);
        $response = $this->app->handle($request);
        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertJsonData($mockProduct + ['id' => $id], $response);
    }

    public function testProductAlreadyCreated()
    {
        $repo = new ProductMemoryRepository();
        $product = $repo->getById(1);
        $mockProduct = $product;
        unset($mockProduct['id']);
        $request = $this->createJsonRequest('POST', '/products', $mockProduct);
        $response = $this->app->handle($request);
        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertJsonData($product, $response);
    }

    public function testInvalidProductPayload()
    {
        $repo = new ProductMemoryRepository();
        $product = $repo->getById(1);
        $mockProduct = $product;
        unset($mockProduct['id'], $mockProduct['species']);
        $request = $this->createJsonRequest('POST', '/products', $mockProduct);
        $response = $this->app->handle($request);
        $this->assertSame(StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY, $response->getStatusCode());
        $this->assertResponseContains('The field species is required', $response);
    }

    public function testInvalidTypeInProductPayload()
    {
        $repo = new ProductMemoryRepository();
        $product = $repo->getById(1);
        $mockProduct = $product;
        unset($mockProduct['id']);
        $mockProduct['species'] = 1;
        $request = $this->createJsonRequest('POST', '/products', $mockProduct);
        $response = $this->app->handle($request);
        $this->assertSame(StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY, $response->getStatusCode());
        $this->assertResponseContains(
            'Unknown value [1] for [species], supported values are [pine, spruce, fir, birch, apple_tree]',
            $response
        );
    }

    public function testFormatProductPayload()
    {
        $mockProduct = [
            "species" => "pine",
            "grading_system" => "nordic_blue",
            "grade" => "AAAAA",
            "drying" => "fresh",
            "treatment" => "null",
            "thickness" => 50,
            "width" => 120,
            "length" => 1200
        ];

        $repo = new ProductMemoryRepository();
        $products = $repo->getAll();
        $id = count($products) + 1;
        $request = $this->createJsonRequest('POST', '/products', $mockProduct);
        $response = $this->app->handle($request);
        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $mockProduct['grade'] = strtolower($mockProduct['grade']);
        $this->assertJsonData($mockProduct + ['id' => $id], $response);
    }

    public function testProductUnprocessableEntity()
    {
        $request = $this->createRequest('GET', '/products/aaaaa');
        $response = $this->app->handle($request);
        $this->assertSame(StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }

    public function testProductNotFound()
    {
        $request = $this->createRequest('GET', '/products/100');
        $response = $this->app->handle($request);
        $this->assertSame(StatusCodeInterface::STATUS_NOT_FOUND, $response->getStatusCode());
    }

    public function testPageNotFound(): void
    {
        $request = $this->createRequest('GET', '/nada');
        $request->withHeader('Accept', 'application/json');
        $response = $this->app->handle($request);
        $this->assertSame(StatusCodeInterface::STATUS_NOT_FOUND, $response->getStatusCode());
    }
}

