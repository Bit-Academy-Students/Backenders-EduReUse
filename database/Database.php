<?php

namespace Database;

use PDO;
// use SQLite3;

class Database
{
    protected PDO $database;

    public function __construct()
    {
        $this->database = new PDO('sqlite:' . __DIR__ . '/database.sqlite');
        // $this->database = new SQLite3(__DIR__ . '/database.sqlite');
    }

    public function getConnection(): PDO
    {
        return $this->database;
    }
}
