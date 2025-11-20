<?php

use Database\Database;

require_once __DIR__ . '/../vendor/autoload.php';

$db = new Database('edureuse');

$db->connect()->query("DROP DATABASE IF EXISTS edureuse");

if (!$db->databaseExists('edureuse')) {
    $db->connect()->query("CREATE DATABASE edureuse");
}

$db->connect()->query("USE edureuse");

// run migrations in migrations folder
$folder = '/migrations';
$dir = new DirectoryIterator(__DIR__ . $folder);

// sort migrations on filename
$migrations = [];
foreach ($dir as $file) {
    if ($file->isFile()) {
        $migrations[] = $file->getPathname();
    }
}

sort($migrations);

foreach ($migrations as $migration) {
    include $migration;
    echo 'Add ..................... ' . basename($migration) . PHP_EOL;
}
