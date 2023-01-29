<?php

declare(strict_types=1);

namespace App\Application\Actions\Auth;

use Ahc\Jwt\JWT;
use App\Application\Actions\Action;
use App\Domain\User\UserRepository;
use Psr\Log\LoggerInterface;

abstract class AuthAction extends Action
{
    protected UserRepository $userRepository;

    protected JWT $jwt;

    public function __construct(LoggerInterface $logger, UserRepository $userRepository, JWT $jwt)
    {
        parent::__construct($logger);
        $this->userRepository = $userRepository;
        $this->jwt = $jwt;
    }
}
