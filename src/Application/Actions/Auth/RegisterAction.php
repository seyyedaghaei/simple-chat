<?php

declare(strict_types=1);

namespace App\Application\Actions\Auth;

use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class RegisterAction extends AuthAction
{
    protected array $params = ['username', 'firstName', 'lastName', 'password'];

    /**
     * {@inheritdoc}
     * @throws HttpBadRequestException
     */
    protected function action(): Response
    {
        $body = $this->getFormData();

        $user = $this->userRepository->register(
            $body['username'],
            $body['firstName'],
            $body['lastName'],
            $body['password'],
        );

        return $this->respondWithData($user);
    }
}
