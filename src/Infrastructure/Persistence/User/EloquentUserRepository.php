<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\User;

use App\Domain\User\User;
use App\Domain\User\UserAlreadyExists;
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

    /**
     * {@inheritdoc}
     */
    public function login(string $username, string $password): User
    {
        $user = User::query()->where([
            'username' => $username,
            'password' => $this->hashPassword($password),
        ])->first();
        if (!isset($user)) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function register(string $username, string $firstName, string $lastName, string $password): User
    {
        try {
            return User::query()->create([
                'username' => $username,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'password' => $this->hashPassword($password),
            ]);
        } catch (\Exception $e) {
            throw new UserAlreadyExists();
        }
    }

    protected function hashPassword(string $password)
    {
        return hash('sha256', $password);
    }

    public function chats(User $user): array {
        $sentChats = $user->sentMessages()->distinct()->get('to_id')->map(function ($a) {
            return $a['to_id'];
        });

        $receivedChats = $user->receivedMessages()->distinct()->get('from_id')->map(function ($a) {
            return $a['from_id'];
        });

        return User::query()->findMany($sentChats->merge($receivedChats)->unique()->all())->all();
    }
}
