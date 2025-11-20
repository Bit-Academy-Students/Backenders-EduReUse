<?php

namespace Database\seeders;

use PDO;

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

    protected function now()
    {
        return date('Y-m-d G:i:s');
    }
}
