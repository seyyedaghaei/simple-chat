<?php

declare(strict_types=1);

namespace Tests\Application\Actions\User;

use App\Application\Actions\ActionError;
use App\Application\Actions\ActionPayload;
use App\Application\Handlers\HttpErrorHandler;
use App\Domain\User\User;
use App\Domain\User\UserNotFoundException;
use App\Domain\User\UserRepository;
use DI\Container;
use Exception;
use Slim\Middleware\ErrorMiddleware;
use Tests\TestCase;

class ViewUserActionTest extends TestCase
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
            ->findUserOfId(1)
            ->willReturn($user)
            ->shouldBeCalledOnce();
        $userRepositoryProphecy
            ->findUserOfId(2)
            ->willReturn($loggedInUser)
            ->shouldBeCalledOnce();

        $container->set(UserRepository::class, $userRepositoryProphecy->reveal());

        $request = $this->createRequest('GET', '/users/1');
        $request = $this->addToken($app, $request, 2);
        $response = $app->handle($request);

        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(200, $user);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }

    public function testActionThrowsUserNotFoundException()
    {
        $app = $this->getAppInstance();

        $callableResolver = $app->getCallableResolver();
        $responseFactory = $app->getResponseFactory();

        $errorHandler = new HttpErrorHandler($callableResolver, $responseFactory);
        $errorMiddleware = new ErrorMiddleware($callableResolver, $responseFactory, true, false, false);
        $errorMiddleware->setDefaultErrorHandler($errorHandler);

        $app->add($errorMiddleware);

        /** @var Container $container */
        $container = $app->getContainer();

        $loggedInUser = User::fromArray([
            'id' => 2,
            'username' => 'steve_jobs',
            'first_name' => 'Steve',
            'last_name' => 'Jobs',
        ]);

        $userRepositoryProphecy = $this->prophesize(UserRepository::class);
        $userRepositoryProphecy
            ->findUserOfId(1)
            ->willThrow(new UserNotFoundException())
            ->shouldBeCalledOnce();

        $userRepositoryProphecy
            ->findUserOfId(2)
            ->willReturn($loggedInUser)
            ->shouldBeCalledOnce();

        $container->set(UserRepository::class, $userRepositoryProphecy->reveal());

        $request = $this->createRequest('GET', '/users/1');
        $request = $this->addToken($app, $request, 2);
        $response = $app->handle($request);

        $payload = (string) $response->getBody();
        $expectedError = new ActionError(ActionError::RESOURCE_NOT_FOUND, 'The user you requested does not exist.');
        $expectedPayload = new ActionPayload(404, null, $expectedError);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}
