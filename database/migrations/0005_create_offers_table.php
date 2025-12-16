<?php

use Database\Database;

$db = new Database();

$db->connect()->query("USE " . $db->getDbName());
$db->connect()->exec(
    "CREATE TABLE IF NOT EXISTS offers (
    id INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    titel varchar(100) NOT NULL,
    staat_id INT NOT NULL,
    hoeveelheid INT NOT NULL,
    beschrijving TEXT NULL,
    postcode varchar(7) NOT NULL,
    is_completed TINYINT NOT NULL DEFAULT 0,
    date_created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_modified DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    image_url VARCHAR(255) NULL,
    type_id INT NOT NULL,
    user_id INT NOT NULL,
    product_url TEXT NULL,
    FOREIGN KEY (staat_id) REFERENCES product_states(id),
    FOREIGN KEY (type_id) REFERENCES types(id),
    FOREIGN KEY (user_id) REFERENCES users(id))"
);
