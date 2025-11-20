<?php

use Database\Database;

$db = new Database('edureuse');

$db->connect()->query("USE edureuse");
$db->connect()->exec(
    "CREATE TABLE IF NOT EXISTS product_states (
    id INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    label varchar(25) NOT NULL)"
);
