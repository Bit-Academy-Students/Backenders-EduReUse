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
        if (!($_POST['model'])) {
            throw new Exception('Geen model meegegeven');
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
        $model = $_POST['model'];
        $type = $_POST['type'];
        $hoeveelheid = $_POST['hoeveelheid'];
        $postcode = $_POST['postcode'];
        $deadline = $_POST['deadline'];
        $beschrijving = $_POST['beschrijving'];

        $sql = "INSERT INTO needs (model, type_id, hoeveelheid, postcode, beschrijving, deadline, user_id)
            VALUES (:model, :typeId, :hoeveelheid, :postcode, :deadline, :userId)";

        $exec = $conn->prepare($sql);
        $exec->execute([
            'model' => $model,
            'typeId' => $type,
            'hoeveelheid' => $hoeveelheid,
            'postcode' => $postcode,
            'deadline' => $deadline,
            'userId' => 1,
        ]);

        header('location: adminPage.php');
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

    <link rel="stylesheet" type="text/css" href="resources/style.css">
</head>

<body>
    <div class="container">
        <div id="header">
            <h1>Behoefte formulier</h1>
        </div>
        <div id="content">
            <form method="post">
                <div>
                    <label for="model">Model:</label>
                    <input type="text" id="model" name="model" required>
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
                    <input type="date" id="deadline" name="deadline" required>
                </div>

                <input type="submit" name="submit" value="Verzenden">
            </form>
        </div>
    </div>
</body>

</html>