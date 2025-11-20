<?php

namespace Database\seeders;

use PDO;
use SQLite3;

abstract class Seeder
{
    protected PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function truncate(string $table): void
    {
        $this->db->query("USE edureuse");
        $this->db->exec("DELETE FROM $table");
    }
}
