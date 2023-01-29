<?php

declare(strict_types=1);

use App\Application\Actions\Auth\LoginAction;
use App\Application\Actions\Auth\RegisterAction;
use App\Application\Actions\Message\ListMessagesAction;
use App\Application\Actions\User\ChatsAction;
use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\MeAction;
use App\Application\Actions\User\ViewUserAction;
use App\Application\Middleware\AuthMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });

    $app->post('/register', RegisterAction::class);
    $app->post('/login', LoginAction::class);

    $app->group('/users', function (Group $group) {
        $group->get('/me', MeAction::class);
        $group->get('/chats', ChatsAction::class);
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    })->add(AuthMiddleware::class);

    $app->group('/messages', function (Group $group) {
        $group->get('/{id}', ListMessagesAction::class);
    })->add(AuthMiddleware::class);
};
