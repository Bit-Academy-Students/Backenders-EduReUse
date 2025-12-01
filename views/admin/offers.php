<?php

use Database\Database;

$db = new Database();
$conn = $db->connect();
$conn->query("USE " . $db->getDbName());

// offers
$sql = "SELECT offers.id, offers.titel, product_states.label, offers.hoeveelheid, offers.beschrijving, offers.postcode, offers.date_created, offers.date_modified, types.type, users.naam
FROM `offers`

INNER JOIN product_states ON offers.staat_id = product_states.id
INNER JOIN types ON offers.type_id = types.id
INNER JOIN users ON offers.user_id = users.id

ORDER BY offers.id DESC";

$offers = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin page</title>

    <link rel="stylesheet" href="/../resources/style.css">
</head>

<body>
    <?php require_once __DIR__ . '/../components/header.php' ?>

    <div style="display: flex;">
        <aside>
            <?php require_once __DIR__ . '/../components/leftSidebar.php' ?>
        </aside>

        <div style="background-color: white;">
            <div>
                <!-- header -->
                <button type="button">Delete</button>
                <button type="button">Filters</button>
                <input id="search" type="text" placeholder="Search">
            </div>

            <div id="container">
                <table>
                    <tr>
                        <th></th>
                        <th>(school)naam</th>
                        <th>Product type</th>
                        <th>Omschrijving</th>
                        <th>Datum toegevoegd</th>
                        <th>Datum gewijzigd</th>
                    </tr>

                    <?php if ($offers) { ?>
                        <?php foreach ($offers as $offer) { ?>
                            <tr>
                                <td><a href="/admin/offers/<?= $offer['id'] ?>">></a></td>
                                <td><?= $offer['naam'] ?></td>
                                <td><?= $offer['type'] ?></td>
                                <td><?= $offer['beschrijving'] ?></td>
                                <td><?= $offer['postcode'] ?></td>
                                <td><?= explode(' ', $offer['date_created'])[0] ?></td>
                                <td><?= explode(' ', $offer['date_modified'])[0] ?></td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
</body>

</html>