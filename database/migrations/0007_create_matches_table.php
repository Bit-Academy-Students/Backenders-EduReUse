<?php

use Database\Database;

$db = new Database();

$db->connect()->query("USE edureuse");
$db->connect()->exec(
    "CREATE TABLE IF NOT EXISTS matches (
    id INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    date_created DATETIME NOT NULL,
    date_pickup DATETIME NULL,
    date_refurbished DATETIME NULL,
    date_delivered DATETIME NULL,
    date_modified DATETIME NOT NULL,
    status_id INT NOT NULL,
    need_id INT NOT NULL,
    offer_id INT NOT NULL,
    FOREIGN KEY (status_id) REFERENCES statuses(id),
    FOREIGN KEY (need_id) REFERENCES needs(id),
    FOREIGN KEY (offer_id) REFERENCES offers(id))"
);
