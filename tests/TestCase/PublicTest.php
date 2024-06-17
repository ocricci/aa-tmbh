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

    public function testPageNotFound(): void
    {
        $request = $this->createRequest('GET', '/nada');
        $request->withHeader('Accept', 'application/json');
        $response = $this->app->handle($request);
        $this->assertSame(StatusCodeInterface::STATUS_NOT_FOUND, $response->getStatusCode());
    }
}

