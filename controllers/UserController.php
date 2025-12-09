<?php

namespace Controllers;

use Database\Database;
use Database\seeders\Seeder;
use Exception;
use PDO;

class UserController extends Seeder
{
    private Database $database;
    private PDO $conn;
    private array $user;

    public function __construct()
    {
        $this->database = new Database();
        $this->conn = $this->database->connect();
        $this->conn->query("USE " . $this->database->getDbName());

        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $_SESSION['id']]);
        $this->user = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function editPass()
    {
        if (empty($_POST['current-pass']) || empty($_POST['new-pass']) || empty($_POST['repeat-pass'])) {
            throw new Exception("Niet alle velden zijn ingevuld");
        }

        $currentPass = $_POST['current-pass'];
        $newPass = $_POST['new-pass'];
        $repeatPass = $_POST['repeat-pass'];

        if (!$this->user || !(($currentPass === $this->user['wachtwoord']) || password_verify($currentPass, $this->user['wachtwoord']))) {
            throw new Exception("Huidige wachtwoord is onjuist");
        }

        if ($newPass !== $repeatPass) {
            throw new Exception("Nieuwe wachtwoorden zijn niet gelijk");
        }

        $sql = "UPDATE users SET wachtwoord = :pass WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'pass' => $newPass,
            'id' => $this->user['id'],
        ]);

        header('location: /logout');
        exit();
    }

    public function editUser()
    {
        // 
    }
}
