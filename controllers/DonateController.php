<?php

namespace Controllers;

use Database\Database;
use Database\seeders\Seeder;
use Exception;

class DonateController extends Seeder
{
    private Database $database;
    private $conn;

    public function __construct()
    {
        $this->database = new Database();
        $this->conn = $this->database->connect();
    }

    public function post()
    {
        $this->unsetSessionError('error');

        $this->conn->query("USE " . $this->database->getDbName());

        $unallowedChars = [
            '?',
            '&',
        ];
        foreach ($unallowedChars as $char) {
            if (str_contains($_POST['titel'], $char)) {
                throw new Exception("Karakter '$char' is niet toegestaan");
            }
        }

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
        $postcode = strtoupper($_POST['postcode']);
        $url = $_POST['product_url'];

        // only add image to database if an image was uploaded 
        $image_name = '';
        if (!empty($_FILES['image']['name'])) {
            $image_name = $this->storeImg($titel);
        }

        $sql = "INSERT INTO offers (titel, staat_id, hoeveelheid, beschrijving, postcode, date_created, date_modified, image_url, type_id, user_id, product_url, is_completed)
            VALUES (:titel, :staatId, :hoeveelheid, :beschrijving, :postcode, :dateCreated, :dateModified, :image_url, :typeId, :userId, :productUrl, :isCompleted)";

        $now = $this->now();
        $exec = $this->conn->prepare($sql);
        $exec->execute([
            'titel' => $titel,
            'staatId' => $staat,
            'hoeveelheid' => $aantal,
            'beschrijving' => $beschrijving,
            'postcode' => $postcode,
            'dateCreated' => $now,
            'dateModified' => $now,
            'image_url' => $image_name,
            'typeId' => $type,
            'userId' => $_SESSION['id'],
            'productUrl' => $url,
            'isCompleted' => 0,
        ]);

        header('location: /user/posts');
        exit();
    }

    private function storeImg(string $titel): string
    {
        $img_name = $_FILES['image']['name'];
        $tmp_name = $_FILES['image']['tmp_name'];

        $error = $_FILES['image']['error'];
        if ($error !== 0) {
            throw new Exception($error);
        }

        // check if image is allowed
        $imgExtension = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'svg'];
        if (!in_array(strtolower($imgExtension), $allowedExtensions)) {
            throw new Exception('Alleen JPG, JPEG & PNG bestanden zijn toegestaan');
        }

        // move img to uploads folder
        $newImgName = preg_replace('/\s+/', '_', uniqid($titel) . ".$imgExtension");
        $imgUploadPath = '../public/src/uploads/' . $newImgName;
        move_uploaded_file($tmp_name, $imgUploadPath);

        return $newImgName;
    }
}
