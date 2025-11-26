<?php

namespace Controllers;

use Database\Database;
use Exception;

class DonateController
{
    private Database $db;
    private $conn;

    public function __construct()
    {
        $this->db = new Database();
        $this->conn = $this->db->connect();
    }

    public function post()
    {
        if ($_SESSION['error']) {
            unset($_SESSION['error']);
        }

        $this->conn->query("USE " . $this->db->getDbName());

        if (!($_POST['titel'])) {
            throw new Exception('Geen titel meegegeven');
        }
        if (!($_POST['type'])) {
            throw new Exception('Geen product type meegegeven');
        }
        if (!($_POST['aantal'])) {
            throw new Exception('Geen hoeveelheid meegegeven');
        }
        if (!($_POST['staat'])) {
            throw new Exception('Geen product staat meegegeven');
        }
        if (!($_POST['postcode'])) {
            throw new Exception('Geen postcode meegegeven');
        }

        $titel = $_POST['titel'];
        $type = $_POST['type'];
        $aantal = $_POST['aantal'];
        $beschrijving = $_POST['beschrijving'];
        $staat = $_POST['staat'];
        $postcode = $_POST['postcode'];

        $sql = "INSERT INTO offers (titel, staat_id, hoeveelheid, beschrijving, postcode, type_id, user_id)
            VALUES (:titel, :staatId, :hoeveelheid, :beschrijving, :postcode, :typeId, :userId)";

        $exec = $this->conn->prepare($sql);
        $exec->execute([
            'titel' => $titel,
            'staatId' => $staat,
            'hoeveelheid' => $aantal,
            'beschrijving' => $beschrijving,
            'postcode' => $postcode,
            'typeId' => $type,
            'userId' => 1,
        ]);

        header('location: /admin');
        exit();
    }
}
