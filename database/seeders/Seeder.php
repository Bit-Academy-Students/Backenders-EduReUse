<?php

namespace Database\seeders;

use SQLite3;

abstract class Seeder
{
    protected SQLite3 $db;

    public function __construct(SQLite3 $db)
    {
        $this->db = $db;
    }

    public function truncate(string $table): void
    {
        $this->db->exec("DELETE FROM $table");
    }
}
