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

        // only add path to database if an image was uploaded 
        $image_name = '';
        if (!empty($_FILES['image']['name'])) {
            $image_name = $this->storeImg();
        }

        $titel = $_POST['titel'];
        $type = $_POST['type'];
        $aantal = $_POST['aantal'];
        $beschrijving = $_POST['beschrijving'];
        $staat = $_POST['staat'];
        $postcode = strtoupper($_POST['postcode']);
        $url = $_POST['product_url'];

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

    private function storeImg()
    {
        $target_dir = "../public/src/uploads/";
        $image_name = $target_dir . basename($_FILES["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
            throw new Exception("Bestand is geen afbeelding.");
        }

        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            $uploadOk = 0;
            throw new Exception("Sorry, alleen JPG, JPEG & PNG bestanden zijn toegestaan.");
        }

        if (file_exists($image_name)) {
            throw new Exception("Sorry, deze bestandsnaam bestaat al.");
            $uploadOk = 0;
        }

        // controleer of uploadOk 0 is door een error
        if ($uploadOk == 0) {
            throw new Exception("Sorry, uw bestand is niet geupload.");
        } else {
            // TODO: fix image upload
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $image_name)) {
                // echo "Bestand " . htmlspecialchars(basename($_FILES["image"]["name"])) . " is geupload.";
                return basename($_FILES['image']['name']);
            } else {
                throw new Exception("Sorry, er ging ets mis bij het uploaden van uw bestand.");
            }
        }
    }
}
