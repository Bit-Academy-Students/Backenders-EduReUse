<?php

namespace Database;

use Dotenv\Dotenv;
use PDO;

class Database
{
    private PDO $database;
    private string $dbName;

    public function __construct()
    {
        // load env variables
        $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
        $dotenv->load();

        $this->database = new PDO('mysql:host=localhost;', $_ENV['DB_USER'], $_ENV['DB_PASS']);
        $this->dbName = $_ENV['DB_NAME'];
    }

    /**
     * Returns used database name
     *
     * @return string
     */
    public function getDbName(): string
    {
        return $this->dbName;
    }

    /**
     * Create a connection with the database
     *
     * @return PDO which can be used for queries
     */
    public function connect(): PDO
    {
        return $this->database;
    }

    /**
     * Checks if database exists
     *
     * @param string $dbName
     * @return bool
     */
    public function databaseExists(string $dbName): bool
    {
        $stmt = $this->database->prepare(
            "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = :dbName"
        );
        $stmt->bindParam(':dbName', $dbName);
        $stmt->execute();

        return $stmt->fetch() !== false;
    }
}
