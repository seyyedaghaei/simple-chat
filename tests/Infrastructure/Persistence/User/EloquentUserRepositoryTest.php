<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Persistence\User;

use App\Domain\User\User;
use App\Domain\User\UserAlreadyExists;
use App\Domain\User\UserNotFoundException;
use App\Infrastructure\Persistence\User\EloquentUserRepository;
use Tests\TestCase;

class EloquentUserRepositoryTest extends TestCase
{
    /**
     * @throws UserNotFoundException
     * @throws UserAlreadyExists
     */
    public function testFindAll()
    {
        $user = User::fromArray([
            'id' => 1,
            'username' => 'bill_gates',
            'first_name' => 'Bill',
            'last_name' => 'Gates',
        ]);

        $userRepository = new EloquentUserRepository();
        $userRepository->register('bill_gates', 'Bill', 'Gates', 'password');

        $this->assertEquals(json_encode([$user]), json_encode($userRepository->findAll()));
    }

    /**
     * @throws UserNotFoundException
     * @throws UserAlreadyExists
     */
    public function testFindAllUsersByDefault()
    {
        $users = [
            1 => User::fromArray([
                'id' => 1,
                'username' => 'bill_gates',
                'first_name' => 'Bill',
                'last_name' => 'Gates',
            ]),
            2 => User::fromArray([
                'id' => 2,
                'username' => 'steve_jobs',
                'first_name' => 'Steve',
                'last_name' => 'Jobs',
            ]),
        ];

        $userRepository = new EloquentUserRepository();
        $userRepository->register('bill_gates', 'Bill', 'Gates', 'password');
        $userRepository->register('steve_jobs', 'Steve', 'Jobs', 'password');

        $this->assertEquals(json_encode(array_values($users)), json_encode($userRepository->findAll()));
    }

    /**
     * @throws UserNotFoundException
     * @throws UserAlreadyExists
     */
    public function testFindUserOfId()
    {
        $user = User::fromArray([
            'id' => 1,
            'username' => 'bill_gates',
            'first_name' => 'Bill',
            'last_name' => 'Gates',
        ]);

        $userRepository = new EloquentUserRepository();

        $userRepository->register('bill_gates', 'Bill', 'Gates', 'password');

        $this->assertEquals(json_encode($user), json_encode($userRepository->findUserOfId(1)));
    }

    public function testFindUserOfIdThrowsNotFoundException()
    {
        $userRepository = new EloquentUserRepository();
        $this->expectException(UserNotFoundException::class);
        $userRepository->findUserOfId(1);
    }
}
