<?php

use Database\Database;

$db = new Database('edureuse');

$db->connect()->query("USE edureuse");
$db->connect()->exec(
    "CREATE TABLE IF NOT EXISTS types (
    id INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    type varchar(100) NOT NULL)"
);
