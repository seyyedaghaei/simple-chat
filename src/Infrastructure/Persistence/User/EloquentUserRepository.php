<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\User;

use App\Domain\User\User;
use App\Domain\User\UserNotFoundException;
use App\Domain\User\UserRepository;

class EloquentUserRepository implements UserRepository
{

    /**
     * {@inheritdoc}
     */
    public function findAll(): array
    {
        return User::all()->all();
    }

    /**
     * {@inheritdoc}
     */
    public function findUserOfId(int $id): User
    {
        $user = User::query()->find($id)->first();
        if (!isset($user)) {
            throw new UserNotFoundException();
        }

        return $user;
    }
}
