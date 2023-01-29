<?php

declare(strict_types=1);

namespace App\Application\Actions\Auth;

use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class LoginAction extends AuthAction
{
    protected array $params = ['username', 'password'];

    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $body = $this->getFormData();

        $user = $this->userRepository->login($body['username'], $body['password']);

        return $this->respondWithData([
            'token' => $this->jwt->encode(['id' => $user->id]),
        ]);
    }
}
