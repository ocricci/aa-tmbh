<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class ProductController extends AbstractController
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

        $product = $this->action->getById($id);
        if (0 == count($product)) {
            return $this->handleEntityNotFound($response, "Entity not found [{$id}]");
        }

        return $this->handleSuccessResponse($response, $product);
    }

    public function create(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $parsedBody = $request->getParsedBody();
        $validator = $this->action->validate($parsedBody);
        if (false == $validator->isValid()) {
            return $this->handleUnprocessableEntityResponse(
                $response,
                $validator->getMessage()
            );
        }

        $product = $this->action->create($parsedBody);
        return $this->handleSuccessResponse($response, $product);
    }
}

