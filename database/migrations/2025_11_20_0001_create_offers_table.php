<?php

use Database\Database;

require_once __DIR__ . '/../../vendor/autoload.php';

$db = new Database('edureuse');

$db->connect()->query("USE edureuse");
$db->connect()->exec(
    "CREATE TABLE IF NOT EXISTS offers (
    id INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    model varchar(100) NOT NULL,
    staat varchar(25) NOT NULL,
    hoeveelheid INT NOT NULL,
    beschrijving TEXT NOT NULL,
    postcode varchar(7) NOT NULL,
    type_id INT NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (type_id) REFERENCES types(id),
    FOREIGN KEY (user_id) REFERENCES users(id))"
);
