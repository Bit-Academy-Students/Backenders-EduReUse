<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Database\Database;

$db = new Database();
$conn = $db->connect();
$conn->query("USE " . $db->getDbName());

// product states
$sql = "SELECT offers.titel, product_states.label, offers.hoeveelheid, offers.beschrijving, offers.postcode, types.type, users.naam
FROM `offers`
INNER JOIN product_states
ON offers.staat_id = product_states.id
INNER JOIN types
ON offers.type_id = types.id
INNER JOIN users
ON offers.user_id = users.id";
$offers = $conn->query($sql);


// echo '<pre>';
// foreach ($offers as $offer) {
//     print_r($offer);
// }
// echo '</pre>';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="resources/style.css">
</head>

<body>
    <?php require_once  __DIR__ . '/../components/header.php' ?>

    <h1>Admin pagina</h1>

    <div>
        <?php foreach ($offers as $offer) { ?>
            <p>------------------------------------</p>
            <h2>
                <b>Titel: </b> <?= $offer['titel'] ?>
            </h2>

            <p>
                <b>Staat: </b> <?= $offer['label'] ?>
            </p>

            <p>
                <b>Hoeveelheid: </b> <?= $offer['hoeveelheid'] ?>
            </p>

            <p>
                <b>Beschrijving: </b> <?= $offer['beschrijving'] ?>
            </p>

            <p>
                <b>Ophaal postcode:</b> <?= $offer['postcode'] ?>
            </p>

            <p>
                <b>Type</b> <?= $offer['type'] ?>
            </p>

            <p>
                <b>Username: </b> <?= $offer['naam'] ?>
            </p>
        <?php } ?>
    </div>
</body>

</html>