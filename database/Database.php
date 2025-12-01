<?php

namespace Database;

use Dotenv\Dotenv;
use PDO;

class Database
{
    protected PDO $database;
    protected string $dbName;

    public function __construct()
    {
        // load env variables
        $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
        $dotenv->load();

        $this->database = new PDO('mysql:host=localhost;', $_ENV['DB_USER'], $_ENV['DB_PASS']);
        $this->dbName = $_ENV['DB_NAME'];
    }

    public function getDbName(): string
    {
        return $this->dbName;
    }

    public function connect(): PDO
    {
        return $this->database;
    }

    public function databaseExists(string $dbName): bool
    {
        $stmt = $this->database->prepare("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = :dbName");
        $stmt->bindParam(':dbName', $dbName);
        $stmt->execute();

        return $stmt->fetch() !== false;
    }
}
