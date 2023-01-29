<?php

declare(strict_types=1);

namespace Tests\Application\Actions\User;

use App\Application\Actions\ActionPayload;
use App\Domain\User\UserRepository;
use App\Domain\User\User;
use DI\Container;
use Exception;
use Prophecy\Argument;
use Tests\TestCase;

class ChatsActionTest extends TestCase
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

        $loggedInUser = User::fromArray([
            'id' => 2,
            'username' => 'steve_jobs',
            'first_name' => 'Steve',
            'last_name' => 'Jobs',
        ]);

        $userRepositoryProphecy = $this->prophesize(UserRepository::class);
        $userRepositoryProphecy
            ->chats(Argument::any())
            ->willReturn([$user])
            ->shouldBeCalledOnce();
        $userRepositoryProphecy
            ->findUserOfId(2)
            ->willReturn($loggedInUser)
            ->shouldBeCalledOnce();


        $container->set(UserRepository::class, $userRepositoryProphecy->reveal());

        $request = $this->createRequest('GET', '/users/chats');
        $request = $this->addToken($app, $request, 2);
        $response = $app->handle($request);

        $payload = (string)$response->getBody();
        $expectedPayload = new ActionPayload(200, [$user]);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}
