<?php

use Database\Database;

require_once __DIR__ . '/../vendor/autoload.php';

$db = new Database()->getConnection();

// remove existing sqlite file
unlink(__DIR__ . '/database.sqlite');

// run migrations in migrations/
include __DIR__ . '/migrations/2025_11_19_0001_create_users_table.php';
include __DIR__ . '/migrations/2025_11_19_0002_create_types_table.php';
