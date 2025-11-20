<?php

use Database\Database;

require_once __DIR__ . '/../../vendor/autoload.php';

$db = new Database()->getConnection();

$db->exec(
    "CREATE TABLE needs (
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    model varchar(100) NOT NULL,
    hoeveelheid INT NOT NULL,
    postcode varchar(7) NOT NULL,
    type_id INT NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (type_id) REFERENCES types(id),
    FOREIGN KEY (user_id) REFERENCES users(id))"
);
