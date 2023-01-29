<?php

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();
$dotenv->required('DATABASE')->notEmpty();
$dotenv->ifPresent('STAGE')->allowedValues(['DEV', 'TEST', 'PROD']);

if (!str_starts_with($_ENV['DATABASE'], '/')) {
    $_ENV['DATABASE'] = __DIR__ . '/../' . $_ENV['DATABASE'];
}
