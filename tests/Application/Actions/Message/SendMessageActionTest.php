<?php

declare(strict_types=1);

namespace Tests\Application\Actions\Message;

use App\Application\Actions\ActionPayload;
use App\Domain\Message\Message;
use App\Domain\Message\MessageRepository;
use App\Domain\User\UserRepository;
use App\Domain\User\User;
use DI\Container;
use Exception;
use Tests\TestCase;

class SendMessageActionTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testAction()
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $user = User::fromArray([
            'id' => 1,
            'username' => 'bill_gates',
            'first_name' => 'Bill',
            'last_name' => 'Gates',
        ]);

        $message = Message::fromArray([
            'id' => 1,
            'from_id' => 1,
            'to_id' => 2,
            'message' => 'Hello',
        ]);

        $userRepositoryProphecy = $this->prophesize(UserRepository::class);
        $userRepositoryProphecy
            ->findUserOfId(1)
            ->willReturn($user)
            ->shouldBeCalledOnce();

        $messageRepositoryProphecy = $this->prophesize(MessageRepository::class);

        $messageRepositoryProphecy
            ->createMessage(1, 2, 'Hello')
            ->willReturn($message)
            ->shouldBeCalledOnce();

        $container->set(UserRepository::class, $userRepositoryProphecy->reveal());
        $container->set(MessageRepository::class, $messageRepositoryProphecy->reveal());
        $request = $this->createRequest('POST', '/messages');
        $request = $this->addToken($app, $request);
        $request = $request->withParsedBody([
            'to' => 2,
            'message' => 'Hello',
        ]);
        $response = $app->handle($request);

        $payload = (string)$response->getBody();
        $expectedPayload = new ActionPayload(200, $message);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}
