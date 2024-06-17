<?php

namespace App\Test\TestCase;

use PHPUnit\Framework\TestCase;
use Fig\Http\Message\StatusCodeInterface;
use App\Test\Traits\AppTestTrait;

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
}

