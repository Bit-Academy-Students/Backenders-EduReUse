<?php

use Database\Database;

$db = new Database('edureuse');

$db->connect()->query("USE edureuse");
$db->connect()->exec(
    "CREATE TABLE IF NOT EXISTS needs (
    id INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    titel varchar(100) NOT NULL,
    hoeveelheid INT NOT NULL,
    postcode varchar(7) NOT NULL,
    deadline DATE NULL,
    type_id INT NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (type_id) REFERENCES types(id),
    FOREIGN KEY (user_id) REFERENCES users(id))"
);
