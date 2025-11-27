<?php

use Database\Database;

if (!isset($_SESSION['id'])) {
    header('location: /login');
    exit();
}
$db = new Database();
$conn = $db->connect();
$conn->query("USE " . $db->getDbName());

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

        $titel = $_POST['titel'];
        $type = $_POST['type'];
        $aantal = $_POST['aantal'];
        $beschrijving = $_POST['beschrijving'];
        $staat = $_POST['staat'];
        $postcode = $_POST['postcode'];

        $sql = "INSERT INTO offers (titel, staat_id, hoeveelheid, beschrijving, postcode, type_id, user_id)
            VALUES (:titel, :staatId, :hoeveelheid, :beschrijving, :postcode, :typeId, :userId)";

        $exec = $conn->prepare($sql);
        $exec->execute([
            'titel' => $titel,
            'staatId' => $staat,
            'hoeveelheid' => $aantal,
            'beschrijving' => $beschrijving,
            'postcode' => $postcode,
            'typeId' => $type,
            'userId' => 1,
        ]);

        header('location: /adminPage');
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

    <link rel="stylesheet" href="resources/style.css">
</head>

<body>
    <?php require_once __DIR__ . '/components/header.php' ?>

    <div class="container">
        <div id="header">
            <h1>Donatie Formulier</h1>
        </div>

        <div id="content">
            <form method="post">
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

                <input type="submit" name="submit" value="Doneer">
            </form>

            <?php if (isset($_SESSION['error'])) { ?>
                <?= $_SESSION['error'] ?>
                <?php unset($_SESSION['error']) ?>
            <?php } ?>
        </div>
    </div>
</body>

</html>