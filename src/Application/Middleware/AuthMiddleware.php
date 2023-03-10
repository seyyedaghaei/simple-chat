<?php

declare(strict_types=1);

namespace App\Application\Middleware;

use Ahc\Jwt\JWT;
use App\Domain\User\UserRepository;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Exception\HttpUnauthorizedException;

class AuthMiddleware implements Middleware
{
    protected UserRepository $userRepository;

    protected JWT $jwt;

    public function __construct(UserRepository $userRepository, JWT $jwt)
    {
        $this->userRepository = $userRepository;
        $this->jwt = $jwt;
    }

    /**
     * {@inheritdoc}
     */
    public function process(Request $request, RequestHandler $handler): Response
    {
        if (empty($request->getHeaderLine('Authorization'))) {
            throw new HttpUnauthorizedException($request);
        }

        $auth = $request->getHeaderLine('Authorization');
        if (!str_starts_with($auth, 'Bearer ')) {
            throw new HttpUnauthorizedException($request);
        }

        try {
            $session = $this->jwt->decode(substr($auth, 7));
            $user = $this->userRepository->findUserOfId($session['id']);
            $request = $request->withAttribute('user', $user);
        } catch (Exception) {
            throw new HttpUnauthorizedException($request);
        }

        return $handler->handle($request);
    }
}
