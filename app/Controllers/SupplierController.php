<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Actions\SupplierAction;

class SupplierController extends AbstractController
{
    public function getAll(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        return $this->handleSuccessResponse(
            $response,
            (new SupplierAction())->getAll()
        );
    }

    public function getById(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $id = (int) $args['id'] ?? '';

        if (empty($id)) {
            return $this->handleUnprocessableEntityResponse($response, "Invalid id informed");
        }

        $supplier = (new SupplierAction())->getById($id);
        if (0 == count($supplier)) {
            return $this->handleEntityNotFound($response, "Entity not found [{$id}]");
        }

        return $this->handleSuccessResponse($response, $supplier);
    }
}

