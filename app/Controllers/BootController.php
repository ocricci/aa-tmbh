<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Actions\BootAction;

class BootController
{
    public function boot(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $bootAction = new BootAction();
        $bootAction->boot();
        $response->getBody()->write("SQLite3 booted.");
        return $response->withStatus(200);
    }
}

