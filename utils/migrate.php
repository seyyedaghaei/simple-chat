<?php

$createTable = require __DIR__ . '/migration.php';


$createTable("users", function ($table) {
    $table->increments('id');
    $table->string('username', 30);
    $table->timestamps();
});

echo "Migrated Successfully\n";
