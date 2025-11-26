<?php

namespace Database\seeders;

use Dotenv\Dotenv;
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
        // load env variables
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
        $dotenv->load();

        $this->db->query("USE " . $_ENV['DB_NAME']);
        $this->db->exec("DELETE FROM $table");
    }

    protected function now()
    {
        return date('Y-m-d G:i:s');
    }
}
