<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/dotenv.php';

use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\QueryException;

$db_path = $_ENV['DATABASE'];

if (!file_exists($db_path)) {
    $dir = dirname($db_path);

    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }

    $f = fopen($db_path, 'w');
    fclose($f);
}

$dbSettings = [
    'driver' => 'sqlite',
    'database' => $db_path,
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
];

// boot eloquent
$capsule = new Manager();
$capsule->addConnection($dbSettings);
$capsule->setAsGlobal();

/**
 * @param string $table
 * @param Closure $callback
 * @return void
 */
return function (string $table, Closure $callback)
{
    try {
        Manager::table($table)->exists();
    } catch (QueryException $exception) {
                Manager::schema()->create($table, $callback);
    }
};
