<?php

use Database\Database;

require_once __DIR__ . '/../../vendor/autoload.php';

$db = new Database('edureuse');

$db->connect()->query("USE edureuse");
$db->connect()->exec(
    "CREATE TABLE IF NOT EXISTS history_logs (
    id INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    notitie TEXT NOT NULL,
    admin_id INT NOT NULL,
    match_id INT NOT NULL,
    FOREIGN KEY (admin_id) REFERENCES users(id),
    FOREIGN KEY (match_id) REFERENCES matches(id))"
);
