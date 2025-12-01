<?php

use Database\Database;

if (!isset($_SESSION['id'])) {
    header('location: /login');
    exit();
}
$db = new Database();
$conn = $db->connect();
$conn->query("USE " . $db->getDbName());

// needs
$sql = "SELECT needs.id, needs.titel, needs.hoeveelheid, needs.postcode, needs.deadline, needs.date_created, needs.date_modified, types.type, users.naam
FROM `needs`

INNER JOIN types ON needs.type_id = types.id
INNER JOIN users ON needs.user_id = users.id

ORDER BY needs.id DESC";

$needs = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin page</title>

    <link rel="stylesheet" href="/../src/style.css">
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
                        <th>Postcode</th>
                        <th>Datum toegevoegd</th>
                        <th>Datum gewijzigd</th>
                    </tr>

                    <?php if ($needs) { ?>
                        <?php foreach ($needs as $need) { ?>
                            <tr>
                                <td><a href="/admin/needs/<?= $need['id'] ?>">></a></td>
                                <td><?= $need['naam'] ?></td>
                                <td><?= $need['type'] ?></td>
                                <td><?= $need['titel'] ?></td>
                                <td><?= $need['postcode'] ?></td>
                                <td><?= explode(' ', $need['date_created'])[0] ?></td>
                                <td><?= explode(' ', $need['date_modified'])[0] ?></td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
</body>

</html>