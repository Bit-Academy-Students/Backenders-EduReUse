<?php

use Database\Database;

require_once __DIR__ . '/../../vendor/autoload.php';

$db = new Database()->getConnection();

// users migration
$db->exec(
    "CREATE TABLE types (
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    type varchar(100) NOT NULL)"
);
