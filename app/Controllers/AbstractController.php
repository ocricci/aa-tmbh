<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface;
use App\Actions\AbstractAction;

abstract class AbstractController
{
    public function __construct(
        protected AbstractAction $action
    ) {
    }

    public function handleSuccessResponse(
        ResponseInterface $response,
        array $data = []
    ): ResponseInterface {
        $response
            ->getBody()
            ->write(json_encode($data));

        return $response
            ->withHeader('Content-type', 'application/json')
            ->withStatus(200);
    }

    public function handleEntityNotFound(
        ResponseInterface $response,
        string $customErrorMessage = ''
    ): ResponseInterface {
        return $this->unexpectedResponse(
            $response,
            $customErrorMessage,
            404
        );
    }

    public function handleUnprocessableEntityResponse(
        ResponseInterface $response,
        string $customErrorMessage = ''
    ): ResponseInterface {
        return $this->unexpectedResponse(
            $response,
            $customErrorMessage,
            422
        );
    }

    protected function unexpectedResponse(
        ResponseInterface $response,
        string $customErrorMessage = '',
        int $code = 500
    ): ResponseInterface {
         $response
            ->getBody()
            ->write(json_encode(['error' => $customErrorMessage]));

        return $response
            ->withHeader('Content-type', 'application/json')
            ->withStatus($code);
    }
}

