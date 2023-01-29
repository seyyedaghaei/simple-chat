<?php

$createTable = require __DIR__ . '/migration.php';


$createTable("users", function ($table) {
    $table->increments('id');
    $table->string('username', 30)->unique();
    $table->string('first_name', 30);
    $table->string('last_name', 30);
    $table->string('password', 64);
    $table->timestamps();
});

$createTable("messages", function ($table) {
    $table->increments('id');
    $table->integer('from_id')->unsigned();
    $table->foreign('from_id')->references('id')->on('users');
    $table->integer('to_id')->unsigned();
    $table->foreign('to_id')->references('id')->on('users');
    $table->longText('message');
    $table->timestamps();
});

if (!defined('TEST')) {
    echo "Migrated Successfully\n";
}
