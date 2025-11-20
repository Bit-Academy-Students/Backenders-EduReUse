<?php

use Database\Database;

require_once __DIR__ . '/../../vendor/autoload.php';

$db = new Database()->getConnection();

$db->exec(
    "CREATE TABLE matches (
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    date_created DATETIME NOT NULL,
    date_pickup DATETIME NULL,
    date_refurbished DATETIME NULL,
    date_delivered DATETIME NULL,
    date_modified DATETIME NOT NULL,
    postcode varchar(7) NOT NULL,
    need_id INT NOT NULL,
    offer_id INT NOT NULL,
    FOREIGN KEY (need_id) REFERENCES needs(id),
    FOREIGN KEY (offer_id) REFERENCES offers(id))"
);
