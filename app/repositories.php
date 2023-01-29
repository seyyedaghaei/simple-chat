<?php

declare(strict_types=1);

use App\Domain\Message\MessageRepository;
use App\Domain\User\UserRepository;
use App\Infrastructure\Persistence\Message\EloquentMessageRepository;
use App\Infrastructure\Persistence\User\EloquentUserRepository;
use DI\ContainerBuilder;
use function DI\autowire;

return function (ContainerBuilder $containerBuilder) {
    // Here we map our UserRepository interface to its in memory implementation
    $containerBuilder->addDefinitions([
        UserRepository::class => autowire(EloquentUserRepository::class),
        MessageRepository::class => autowire(EloquentMessageRepository::class),
    ]);
};
