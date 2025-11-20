<?php

use Database\Database;

require_once __DIR__ . '/../../vendor/autoload.php';

$db = new Database()->getConnection();

$db->exec(
    "CREATE TABLE history_logs (
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    notitie TEXT NOT NULL,
    admin_id INT NOT NULL,
    match_id INT NOT NULL,
    FOREIGN KEY (admin_id) REFERENCES users(id),
    FOREIGN KEY (match_id) REFERENCES matches(id))"
);
