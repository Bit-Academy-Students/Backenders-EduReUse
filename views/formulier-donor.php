<?php

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

use Database\Database;

$db = new Database('edureuse');
$conn = $db->connect();
$conn->query("USE edureuse");

$id = (int) $_GET['id'];

$sql = "SELECT * FROM users WHERE id = :id";
$stmt = $conn->prepare($sql);
$recordset = $stmt->execute(['id' => $id]);

// product states
$sql = "SELECT * FROM `product_states`";
$states = $conn->query($sql);

// product types
$sql = "SELECT * FROM `types`";
$types = $conn->query($sql);


       




try {
    $donatie = null;
    if (isset($_POST['submit'])) {
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

        $target_dir = "../public/src/uploads/";
        $image_name = $target_dir . basename($_FILES["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));


        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "Bestand is geen afbeelding.";
            $uploadOk = 0;
        }

        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        ) {
            echo "Sorry, alleen JPG, JPEG & PNG bestanden zijn toegestaan.";
            $uploadOk = 0;
        }

        if (file_exists($image_name)) {
        echo "Sorry, deze bestandsnaam bestaat al.";
        $uploadOk = 0;
        }

        // controleer of uploadOk 0 is door een error
        if ($uploadOk == 0) {
        echo "Sorry, uw bestand is niet geupload.";
        
        } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $image_name)) {
            echo "Bestand ". htmlspecialchars( basename( $_FILES["image"]["name"])). " is geupload.";
        } else {
            echo "Sorry, er ging ets mis bij het uploaden van uw bestand.";
        }
        }


        $titel = $_POST['titel'];
        $type = $_POST['type'];
        $aantal = $_POST['aantal'];
        $beschrijving = $_POST['beschrijving'];
        $staat = $_POST['staat'];
        $postcode = $_POST['postcode'];
        $date_created = $_POST['dateCreated'];
        

        $sql = "INSERT INTO offers (titel, staat_id, hoeveelheid, beschrijving, postcode, date_created, image_url, type_id, user_id)
            VALUES (:titel, :staatId, :hoeveelheid, :beschrijving, :postcode, :date_created, :image_url, :typeId, :userId)";

        $exec = $conn->prepare($sql);
        $exec->execute([
            'titel' => $titel,
            'staatId' => $staat,
            'hoeveelheid' => $aantal,
            'beschrijving' => $beschrijving,
            'postcode' => $postcode,
            'date_created' => date('Y-m-d G:i:s'),
            'image_url' => $image_name,
            'typeId' => $type,
            'userId' => $id,
        ]);

        header('location: ../views/aanbod.php?id=' . $id);
        exit();
    }
} catch (Exception $e) {
    echo $e->getmessage();
} catch (PDOException $ex) {
    echo $ex->getMessage();
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doneer</title>

    <link rel="stylesheet" type="text/css" href="src/style.css">
</head>

<body>
    <div class="container">
        <div id="header">
            <h1>Donatie Formulier</h1>
        </div>
        <div id="content">
            <form method="post" enctype="multipart/form-data">
                <div>
                    <label for="titel">Titel:</label>
                    <input type="text" name="titel" id="titel">
                </div>

                <div>
                    <label for="type">Type:</label>
                    <select name="type" id="type">
                        <?php foreach ($types as $type) { ?>
                            <option value="<?= $type['id'] ?>"><?= $type['type'] ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div>
                    <label for="aantal">Aantal:</label>
                    <input type="number" name="aantal" id="aantal">
                </div>

                <div>
                    <label for="beschrijving">Beschrijving:</label>
                    <textarea name="beschrijving" id="beschrijving"></textarea>
                </div>

                <div>
                    <label for="staat">Staat:</label>
                    <select name="staat" id="staat">
                        <?php foreach ($states as $state) { ?>
                            <option value="<?php echo $state['id'] ?>"><?php echo $state['label'] ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div>
                    <label for="postcode">Postcode:</label>
                    <input type="text" name="postcode" id="postcode">
                </div>
                <div>
                <input type="file" name="image" id="image">
                </div>
                <input type="submit" name="submit" value="Doneer">
            </form>
        </div>
    </div>
</body>

</html>
