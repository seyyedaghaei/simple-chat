<?php

declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use Slim\App;

return function (App $app) {
    $container = $app->getContainer();
    $dbSettings = $container->get(SettingsInterface::class)->get('db');

    // boot eloquent
    $capsule = new Illuminate\Database\Capsule\Manager();
    $capsule->addConnection($dbSettings);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();
};
