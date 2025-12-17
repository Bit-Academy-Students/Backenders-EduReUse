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

    /**
     * Adds new Donation to database
     *
     * @return void
     * @throws Exception
     */
    public function post()
    {
        $this->unsetSessionError('error');
        // connect met db
        $this->conn->query("USE " . $this->database->getDbName());

        // error handling
        $unallowedChars = ['?', '&',];
        foreach ($unallowedChars as $char) {
            if (str_contains($_POST['titel'], $char)) {
                throw new Exception("Karakter '$char' is niet toegestaan");
            }
        }

        $titel = (!empty($_POST['titel'])) ? htmlspecialchars($_POST['titel']) : throw new Exception('Geen titel meegegeven');
        $type = (!empty($_POST['type'])) ? htmlspecialchars($_POST['type']) : throw new Exception('Geen product type meegegeven');
        $aantal = (!empty($_POST['aantal'])) ? htmlspecialchars($_POST['aantal']) : throw new Exception('Geen hoeveelheid meegegeven');
        if ($aantal < 0) {
            throw new Exception("Aantal kan niet minder dan 0 zijn");
        }
        $beschrijving = htmlspecialchars($_POST['beschrijving']);
        $staat = (!empty($_POST['staat'])) ? htmlspecialchars($_POST['staat']) : throw new Exception('Geen product staat meegegeven');

        $postcode = (!empty($_POST['postcode'])) ? htmlspecialchars(strtoupper($_POST['postcode'])) : throw new Exception('Geen postcode meegegeven');

        // regex voor postcode
        $pattern = '/^(\d{4})\s?([a-zA-Z]{2})$/';
        if (!preg_match($pattern, $postcode) || strlen($postcode) < 6 || strlen($postcode) > 7) {
            throw new Exception("Verkeerde postcode '$postcode' ingevoerd, houdt het format '1234 AB' aan");
        }

        // reformat postcode
        $replacement = '$1 $2';
        $postcode = preg_replace($pattern, $replacement, $postcode);

        // only add row to database if an image was uploaded
        $image_name = '';
        $url = htmlspecialchars($_POST['product_url']);
        if (empty($_FILES['image']['name'])) {
            throw new Exception("Geen foto geüpload");
        }

        $image_name = $this->storeImg($titel);

        $sql = "INSERT INTO offers (titel, 
            staat_id,
            hoeveelheid,
            beschrijving, 
            postcode, 
            date_created, 
            date_modified, 
            image_url, 
            type_id, 
            user_id, 
            product_url,
            is_completed)
        VALUES (
            :titel,
            :staatId,
            :hoeveelheid, 
            :beschrijving, 
            :postcode, 
            :dateCreated, 
            :dateModified, 
            :image_url, 
            :typeId, 
            :userId, 
            :productUrl, 
            :isCompleted
        )";

        $exec = $this->conn->prepare($sql);
        $exec->execute([
            'titel' => $titel,
            'staatId' => $staat,
            'hoeveelheid' => $aantal,
            'beschrijving' => $beschrijving,
            'postcode' => $postcode,
            'dateCreated' => $this->now(),
            'dateModified' => $this->now(),
            'image_url' => $image_name,
            'typeId' => $type,
            'userId' => $_SESSION['id'],
            'productUrl' => $url,
            'isCompleted' => 0,
        ]);

        header('location: /user/posts');
        exit();
    }

    /**
     * Stores image into local uploads folder
     *
     * @param string $titel
     * @return string
     * @throws Exception
     */
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
