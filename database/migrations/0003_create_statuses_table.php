<?php

use Database\Database;

$db = new Database();

$db->connect()->query("USE " . $db->getDbName());
$db->connect()->exec(
    "CREATE TABLE IF NOT EXISTS statuses (
    id INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    label varchar(25) NOT NULL)"
);
