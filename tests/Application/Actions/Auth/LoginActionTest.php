<?php

declare(strict_types=1);

namespace Tests\Application\Actions\Auth;

use Ahc\Jwt\JWT;
use App\Application\Actions\ActionPayload;
use App\Domain\User\UserRepository;
use App\Domain\User\User;
use DI\Container;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Tests\TestCase;

class LoginActionTest extends TestCase
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
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

        $userRepositoryProphecy = $this->prophesize(UserRepository::class);
        $userRepositoryProphecy
            ->login('bill', 'his_password')
            ->willReturn($user)
            ->shouldBeCalledOnce();

        $container->set(UserRepository::class, $userRepositoryProphecy->reveal());

        $request = $this->createRequest('POST', '/login');
        $request = $request->withParsedBody([
            'username' => 'bill',
            'password' => 'his_password',
        ]);
        $response = $app->handle($request);
        $token = $app->getContainer()->get(JWT::class)->encode(['id' => 1]);

        $payload = (string)$response->getBody();
        $expectedPayload = new ActionPayload(200, ['token' => $token]);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}
