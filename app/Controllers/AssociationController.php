<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class AssociationController extends AbstractController
{
    public function getAll(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {

        $sid = (int) $args['sid'] ?? '';
        if (empty($sid)) {
            return $this->handleUnprocessableEntityResponse(
                $response,
                "Invalid id informed"
            );
        }

        return $this->handleSuccessResponse(
            $response,
            $this->action->getAll($sid)
        );
     }

    public function getById(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {

        $sid = (int) $args['sid'] ?? '';
        $pid = (int) $args['pid'] ?? '';

        if (empty($sid) || empty($pid)) {
            return $this->handleUnprocessableEntityResponse(
                $response,
                "Invalid id informed"
            );
        }

        return $this->handleSuccessResponse(
            $response,
            $this->action->getById($sid, $pid)
        );
    }

    public function associate(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {

        $sid = (int) $args['sid'] ?? '';
        $pid = (int) $args['pid'] ?? '';

        if (empty($sid) || empty($pid)) {
            return $this->handleUnprocessableEntityResponse(
                $response,
                "Invalid id informed"
            );
        }

        $parsedBody = $request->getParsedBody();
        $validator = $this->action->validate($sid, $pid, $parsedBody);
        if (false == $validator->isValid()) {
            return $this->handleUnprocessableEntityResponse(
                $response,
                $validator->getMessage()
            );
        }

        try {
            $association = $this->action->associate($sid, $pid, $parsedBody);
            return $this->handleSuccessResponse($response, $association);
        } catch (\Exception $e) {
            // @todo log $e
            return $this->unexpectedResponse($response, "Internal server error");
        }
    }
}

