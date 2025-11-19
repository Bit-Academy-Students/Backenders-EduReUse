<?php

use Database\Database;

require_once __DIR__ . '/../../vendor/autoload.php';

$db = new Database()->getConnection();

$db->exec(
    "CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    naam varchar(50) NOT NULL,
    email varchar(50) UNIQUE NOT NULL,
    wachtwoord varchar(255) NOT NULL,
    date_created DATETIME NOT NULL,
    is_admin TINYINT NOT NULL)"
);
