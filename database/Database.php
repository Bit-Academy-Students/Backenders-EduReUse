<?php

namespace Database;

use PDO;
// use SQLite3;

class Database
{
    protected PDO $database;
    protected string $dbName;

    public function __construct(string $dbName)
    {
        $this->database = new PDO('mysql:host=localhost;', 'bit_academy', 'bit_academy');
        $this->dbName = $dbName;

        // $this->database = new SQLite3(__DIR__ . '/database.sqlite');
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

    public function useDatabase()
    {
        $this->database->query("USE $this->dbName");
    }
}
