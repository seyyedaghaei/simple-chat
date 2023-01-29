<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Persistence\Message;

use App\Domain\Message\Message;
use App\Domain\User\UserAlreadyExists;
use App\Domain\User\UserNotFoundException;
use App\Infrastructure\Persistence\Message\EloquentMessageRepository;
use App\Infrastructure\Persistence\User\EloquentUserRepository;
use Tests\TestCase;

class EloquentMessageRepositoryTest extends TestCase
{
    /**
     * @throws UserNotFoundException
     * @throws UserAlreadyExists
     */
    public function testFindAll()
    {
        $message = Message::fromArray([
            'id' => 1,
            'from_id' => 1,
            'to_id' => 2,
            'message' => 'Hello',
        ]);

        $userRepository = new EloquentUserRepository();

        $messageRepository = new EloquentMessageRepository();
        $userRepository->register('bill_gates', 'Bill', 'Gates', 'password');
        $userRepository->register('steve_jobs', 'Steve', 'Jobs', 'password');

        $new_message = $messageRepository->createMessage(1, 2, 'Hello');

        $message->created_at = $new_message->created_at;

        $this->assertEquals(json_encode([$message]), json_encode($messageRepository->findMessageByUser(1, 2)));
    }
}
