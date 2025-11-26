<?php

use Database\Database;

$db = new Database();

$db->connect()->query("USE edureuse");
$db->connect()->exec(
    "CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    naam varchar(50) NOT NULL,
    email varchar(50) UNIQUE NOT NULL,
    wachtwoord varchar(255) NOT NULL,
    date_created DATETIME NOT NULL,
    is_admin TINYINT NOT NULL)"
);
