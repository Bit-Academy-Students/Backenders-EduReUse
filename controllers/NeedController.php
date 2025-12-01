<?php

namespace Controllers;

use Database\Database;
use Database\seeders\Seeder;

class NeedController extends Seeder
{

    private Database $database;
    private $conn;

    public function __construct()
    {
        $this->database = new Database();
        $this->conn = $this->database->connect();
    }

    /**
     * TODO: deze functie moet ervoor zorgen dat 
     * het aanvraagformulier naar de database word gepushed
     * 
     * Tip: zie `DonateController::post()`
     */
    public function post()
    {
        $this->unsetSessionError('error');

        $this->conn->query("USE " . $this->database->getDbName());
        // 
    }
}
