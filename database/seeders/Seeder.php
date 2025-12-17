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

    /**
     * Truncates table from database
     *
     * @param string $table
     * @return void
     */
    public function truncate(string $table): void
    {
        // load env variables
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
        $dotenv->load();

        $this->db->query("USE " . $_ENV['DB_NAME']);
        $this->db->exec("DELETE FROM $table");
    }

    /**
     * Get current timestamp
     *
     * Can be used for a 'DATETIME' database row
     *
     * @return string
     */
    protected function now()
    {
        return date('Y-m-d G:i:s');
    }

    /**
     * Delete a session error
     *
     * @param $sessionVariable
     * @return void
     */
    protected function unsetSessionError($sessionVariable): void
    {
        if (isset($SESSION[$sessionVariable])) {
            unset($_SESSION[$sessionVariable]);
        }
    }
}
