<?php

declare(strict_types=1);

namespace App\Application\Middleware;

use Ahc\Jwt\JWT;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class SessionMiddleware implements Middleware
{
    protected JWT $jwt;

    public function __construct(JWT $jwt)
    {
        $this->jwt = $jwt;
    }

    /**
     * {@inheritdoc}
     */
    public function process(Request $request, RequestHandler $handler): Response
    {
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $auth = $_SERVER['HTTP_AUTHORIZATION'];
            if (str_starts_with($auth, 'Bearer ')) {
                try {
                    $session = $this->jwt->decode(substr($auth, 7));
                    unset($session['exp']);
                    $request = $request->withAttribute('session', $session);
                } catch (\Exception $e) {
                }
            }
        }

        return $handler->handle($request);
    }
}
