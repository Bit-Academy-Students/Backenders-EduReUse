<?php

use Database\Database;

$db = new Database();
$conn = $db->connect();
$conn->query("USE " . $db->getDbName());

// offers & needs
$sql = "SELECT 
    'Offer' AS type, -- Geeft aan dat het een offer is
    offers.id,
    offers.titel,
    product_states.label AS staat,
    offers.hoeveelheid,
    offers.beschrijving,
    offers.postcode,
    NULL AS deadline, -- Placeholder voor de deadline van Needs
    offers.date_created,
    offers.date_modified,
    types.type AS product_type,
    users.naam AS user_name
FROM offers
INNER JOIN product_states ON offers.staat_id = product_states.id
INNER JOIN types ON offers.type_id = types.id
INNER JOIN users ON offers.user_id = users.id

UNION ALL

SELECT 
    'Need' AS type, -- Geeft aan dat het een need is
    needs.id AS id, -- Placeholder voor de ID van Offers
    needs.titel,
    NULL AS staat, -- Placeholder voor de staat van Offers
    needs.hoeveelheid,
    NULL AS beschrijving, -- Placeholder voor de beschrijving van Offers
    needs.postcode,
    needs.deadline,
    needs.date_created,
    needs.date_modified,
    types.type AS product_type,
    users.naam AS user_name
FROM needs
INNER JOIN types ON needs.type_id = types.id
INNER JOIN users ON needs.user_id = users.id

ORDER BY date_created DESC";

$rows = $conn->query($sql);

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
                        <th>Type</th>
                        <th>ID</th>
                        <th>Titel</th>
                        <th>Staat</th>
                        <th>Hoeveelheid</th>
                        <th>Beschrijving</th>
                        <th>Postcode</th>
                        <th>Deadline</th>
                        <th>Datum Gecreëerd</th>
                        <th>Datum Gewijzigd</th>
                        <th>Product Type</th>
                        <th>Gebruiker</th>
                    </tr>

                    <?php if ($rows) { ?>
                        <?php foreach ($rows as $row) { ?>
                            <tr>
                                <td><?= $row['type'] ?></td>
                                <td><?= $row['id'] ?? '-' ?></td>
                                <td><?= $row['titel'] ?></td>
                                <td><?= $row['staat'] ?? '-' ?></td>
                                <td><?= $row['hoeveelheid'] ?></td>
                                <td><?= $row['beschrijving'] ?? '-' ?></td>
                                <td><?= $row['postcode'] ?></td>
                                <td><?= $row['deadline'] ?? '-' ?></td>
                                <td><?= explode(' ', $row['date_created'])[0] ?></td>
                                <td><?= explode(' ', $row['date_modified'])[0] ?></td>
                                <td><?= $row['product_type'] ?></td>
                                <td><?= $row['user_name'] ?></td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
</body>

</html>