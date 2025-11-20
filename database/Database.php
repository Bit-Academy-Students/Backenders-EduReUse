<?php

namespace Database;

use SQLite3;

class Database
{
    protected SQLite3 $database;

    public function __construct()
    {
        $this->database = new SQLite3(__DIR__ . '/database.sqlite');
    }

    public function getConnection(): SQLite3
    {
        return $this->database;
    }
}
