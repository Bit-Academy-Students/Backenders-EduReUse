<?php

use Database\Database;

require_once __DIR__ . '/../vendor/autoload.php';

$db = new Database()->getConnection();

// remove existing sqlite file
unlink(__DIR__ . '/database.sqlite');

// run migrations in migrations/
$folder = '/migrations';
$dir = new DirectoryIterator(__DIR__ . $folder);
foreach ($dir as $filename) {
    if ($filename->isFile()) {
        include __DIR__ . "$folder/$filename";
    }
}
