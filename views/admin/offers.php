<?php

use Database\Database;

if (!isset($_SESSION['id'])) {
    header('location: /login');
    exit();
}
$db = new Database();
$conn = $db->connect();
$conn->query("USE " . $db->getDbName());

// check if user is admin
$sql = "SELECT * FROM users WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([
    'id' => $_SESSION['id'],
]);

$user = $stmt->fetch();
if (!$user['is_admin']) {
    http_response_code(403);
    header('location: /404');
    exit();
}

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
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin page</title>

    <link rel="stylesheet" href="/../src/output.css">
    <?php require_once __DIR__ . '/../components/fontawesome-link.php' ?>
</head>

<body class="bg-gray-100">
    <?php require_once __DIR__ . '/../components/header.php' ?>

    <div class="flex">
        <?php require_once __DIR__ . '/../components/leftSidebar.php' ?>

        <div class="bg-white p-4 rounded-lg m-5 shadow-lg">
            <div class="flex items-center justify-around pb-3 mb-5 border-b-1 border-gray-300">
                <h1 class="font-bold text-3xl">Aanbiedingen</h1>

                <div class="flex gap-5 items-baseline">
                    <button type="button">Delete</button>
                    <button type="button">Filters</button>
                    <input id="search" placeholder="Search" type="text" class="bg-slate-100 mt-2 rounded-md shadow-xs rounded-md py-1.5 px-3">
                </div>
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