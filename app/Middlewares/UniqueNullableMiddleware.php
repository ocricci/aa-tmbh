<?php

namespace App\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * SQLite3 has a problem with NULL in unique keys
 * So, in order to have an optional field this app is using 'null' to represent NULL
 * https://sqlite.org/faq.html#q26
 */
class UniqueNullableMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        if ($request->getMethod() !== "POST") {
            return $handler->handle($request);
        } 

        if (!str_contains((string) $request->getUri(), "products")) {
            return $handler->handle($request);
        }

        $parsedBody = $request->getParsedBody();
        if (empty($parsedBody['treatment'])) {
            $parsedBody['treatment'] = 'null';
            $request = $request->withParsedBody($parsedBody);
        }

        return $handler->handle($request);
    }
}

