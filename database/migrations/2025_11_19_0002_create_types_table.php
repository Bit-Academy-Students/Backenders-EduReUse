<?php

use Database\Database;

require_once __DIR__ . '/../../vendor/autoload.php';

$db = new Database('edureuse');

$db->connect()->query("USE edureuse");
$db->connect()->exec(
    "CREATE TABLE IF NOT EXISTS types (
    id INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    type varchar(100) NOT NULL)"
);
