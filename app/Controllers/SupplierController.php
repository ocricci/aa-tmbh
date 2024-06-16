<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Actions\SupplierAction;

class SupplierController
{
    public function getAll(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $response
            ->getBody()
            ->write(
                json_encode(
                    (new SupplierAction())->getAll()
                )
            );

        return $response
            ->withHeader('Content-type', 'application/json')
            ->withStatus(200);
    }

    public function getById()
    {

    }
}

