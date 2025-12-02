<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Database\Database;

$db = new Database('edureuse');
$conn = $db->connect();
$conn->query("USE edureuse");

$sql = "SELECT * FROM `types`";
$types = $conn->query($sql);

try {
    $behoefte = null;
    if (isset($_POST['submit'])) {
        if (!($_POST['omschrijving'])) {
            throw new Exception('Geen omschrijving meegegeven');
        }
        if (!($_POST['type'])) {
            throw new Exception('Geen product type meegegeven');
        }
        if (!($_POST['hoeveelheid'])) {
            throw new Exception('Geen hoeveelheid meegegeven');
        }
        if (!($_POST['postcode'])) {
            throw new Exception('Geen postcode meegegeven');
        }
        $omschrijving = $_POST['omschrijving'];
        $type = $_POST['type'];
        $hoeveelheid = $_POST['hoeveelheid'];
        $postcode = $_POST['postcode'];
        $deadline = !empty($_POST['deadline']) ? $_POST['deadline'] : null;

        $sql = "INSERT INTO needs (titel, type_id, hoeveelheid, postcode, deadline, user_id, date_created, date_modified)
            VALUES (:titel, :typeId, :hoeveelheid, :postcode, :deadline, :userId, :dateCreated, :dateModified)";

        $exec = $conn->prepare($sql);
        $exec->execute([
            'titel' => $omschrijving,
            'typeId' => $type,
            'hoeveelheid' => $hoeveelheid,
            'postcode' => $postcode,
            'deadline' => $deadline,
            'userId' => 1,
            'dateCreated' => date('Y-m-d G:i:s'),
            'dateModified' => date('Y-m-d G:i:s'),
        ]);

        header('location: /admin');
        exit();
    }
} catch (Exception $e) {
    echo 'Foutmelding: ' . $e->getMessage();
} catch (PDOException $ex) {
    echo $ex->getMessage();
}

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doneer</title>

    <link rel="stylesheet" type="text/css" href="src/style.css">
</head>

<body>
    <?php require_once __DIR__ . '/components/header.php' ?>

    <div class="container">
        <div id="header">
            <h1>Aanvraag formulier</h1>
        </div>
        <div id="content">
            <form method="post">
                <div>
                    <label for="omschrijving">Omschrijving:</label>
                    <input type="text" id="omschrijving" name="omschrijving" required>
                </div>

                <div>
                    <label for="type">Type:</label>
                    <select id="type" name="type" required>
                        <?php foreach ($types as $type) : ?>
                            <option value="<?php echo $type['id']; ?>"><?php echo $type['type']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="hoeveelheid">Hoeveelheid:</label>
                    <input type="number" id="hoeveelheid" name="hoeveelheid" required>
                </div>

                <div>
                    <label for="postcode">Postcode:</label>
                    <input type="text" id="postcode" name="postcode" required>
                </div>

                <div>
                    <label for="deadline">Deadline:</label>
                    <input type="date" id="deadline" name="deadline">
                    <span>(optioneel)</span>
                </div>

                <input type="submit" name="submit" value="Verzenden">
            </form>
        </div>
    </div>
</body>

</html>