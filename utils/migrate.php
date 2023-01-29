<?php

$createTable = require __DIR__ . '/migration.php';


$createTable("users", function ($table) {
    $table->increments('id');
    $table->string('username', 30);
    $table->string('firstName', 30);
    $table->string('lastName', 30);
    $table->string('password', 64);
    $table->timestamps();
});

echo "Migrated Successfully\n";
