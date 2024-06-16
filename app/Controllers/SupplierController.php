<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class SupplierController extends AbstractController
{
    public function getAll(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        return $this->handleSuccessResponse(
            $response,
            $this->action->getAll()
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

        $supplier = $this->action->getById($id);
        if (0 == count($supplier)) {
            return $this->handleEntityNotFound($response, "Entity not found [{$id}]");
        }

        return $this->handleSuccessResponse($response, $supplier);
    }
}

