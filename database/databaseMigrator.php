<?php

use Database\Database;
use Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';

// load env variables
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$db = new Database();
$db->connect()->query("DROP DATABASE IF EXISTS " . $_ENV['DB_NAME']);

if (!$db->databaseExists($_ENV['DB_NAME'])) {
    $db->connect()->query("CREATE DATABASE " . $_ENV['DB_NAME']);
}

$db->connect()->query("USE " . $db->getDbName());

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

// include migration files
foreach ($migrations as $migration) {
    include $migration;
    echo 'Add ..................... ' . basename($migration) . PHP_EOL;
}
