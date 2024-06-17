<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class AssociationController extends AbstractController
{
    public function supplierWithProduct(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {

        $sid = (int) $args['pid'] ?? '';
        $pid = (int) $args['sid'] ?? '';

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

