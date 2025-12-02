<?php

use Database\Database;

if (!isset($_SESSION['id'])) {
    header('location: /login');
    exit();
}

$db = new Database();
$conn = $db->connect();
$conn->query("USE " . $db->getDbName());

// matches
$sql = "SELECT 
    matches.id,
    need_users.naam AS needschool_naam,
    offer_users.naam AS offerschool_naam,
    statuses.label AS status_label,
    types.type,
    offers.postcode AS ophaal_postcode,
    needs.postcode AS aflever_postcode,
    matches.date_created,
    matches.date_modified
FROM `matches`

INNER JOIN needs ON matches.need_id = needs.id
INNER JOIN users AS need_users ON needs.user_id = need_users.id
INNER JOIN offers ON matches.offer_id = offers.id
INNER JOIN users AS offer_users ON offers.user_id = offer_users.id
INNER JOIN statuses ON matches.status_id = statuses.id
INNER JOIN types ON offers.type_id = types.id

ORDER BY matches.id DESC";

$matches = $conn->query($sql);

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
                        <th>Aanvraag naam</th>
                        <th>Donor naam</th>
                        <th>Leveringsstatus</th>
                        <th>Product type</th>
                        <th>Ophaal postcode</th>
                        <th>Aflever postcode</th>
                        <th>Datum toegevoegd</th>
                        <th>Datum gewijzigd</th>
                    </tr>

                    <?php if ($matches) { ?>
                        <?php foreach ($matches as $match) { ?>
                            <tr>
                                <td><a href="/admin/matches/<?= $match['id'] ?>">></a></td>
                                <td><?= $match['needschool_naam'] ?></td>
                                <td><?= $match['offerschool_naam'] ?></td>
                                <td><?= $match['status_label'] ?></td>
                                <td><?= $match['type'] ?></td>
                                <td><?= $match['ophaal_postcode'] ?></td>
                                <td><?= $match['aflever_postcode'] ?></td>
                                <td><?= explode(' ', $match['date_created'])[0] ?></td>
                                <td><?= explode(' ', $match['date_modified'])[0] ?></td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
</body>

</html>