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

// matches
$sql = "SELECT
    matches.*,
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

                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $matches = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

        <div class="bg-white p-4 rounded-lg m-5 shadow-lg w-full">
            <div class="flex items-center justify-around mb-5 border-gray-300">
                <h1 class="font-bold text-gray-700 text-3xl">Matches</h1>

                <div class="flex gap-5 items-baseline">
                    <button type="button">Delete</button>
                    <button type="button">Filters</button>
                    <input id="search" placeholder="Search" type="text" class="bg-slate-100 mt-2 rounded-md shadow-xs rounded-md py-1.5 px-3">
                </div>
            </div>

            <table class="w-full">
                <tr class="*:p-2">
                    <th>Aanvrager naam</th>
                    <th>Donor naam</th>
                    <th>Leveringsstatus</th>
                    <th>Product type</th>
                    <th>Ophaal postcode</th>
                    <th>Aflever postcode</th>
                    <th>Datum toegevoegd</th>
                </tr>

                <?php if ($matches) : ?>
                    <?php foreach ($matches as $match) : ?>
                        <?php if ($match['status_label'] !== 'afgerond') : ?>
                            <tr onclick="document.location.href = '/admin/matches/<?= $match['id'] ?>'"
                                class="*:p-2.5 *:my-2 *:border-t-1 *:border-slate-300 *:text-center cursor-pointer hover:bg-slate-100 transition">
                                <td><?= $match['needschool_naam'] ?></td>
                                <td><?= $match['offerschool_naam'] ?></td>
                                <td class="font-semibold w-fit px-2 py-1 rounded-md shadow-sm
                                <?php if ($match['status_id'] === 1) : ?>
                                    text-emerald-600 bg-emerald-100
                                <?php elseif ($match['status_id'] === 2) : ?>
                                    text-rose-600 bg-rose-100
                                <?php elseif ($match['status_id'] === 3) : ?>
                                    text-sky-600 bg-sky-100
                                <?php elseif ($match['status_id'] === 4) : ?>
                                    text-yellow-600 bg-yellow-100
                                <?php elseif ($match['status_id'] === 5) : ?>
                                    text-indigo-600 bg-indigo-100
                                <?php elseif ($match['status_id'] === 6) : ?>
                                    text-blue-600 bg-blue-100
                                <?php elseif ($match['status_id'] === 7) : ?>
                                    text-pink-600 bg-pink-100
                                <?php elseif ($match['status_id'] === 8) : ?>
                                    text-green-600 bg-green-100
                                <?php endif; ?>
                                ">
                                    <?= $match['status_label'] ?>
                                </td>
                                <td><?= $match['type'] ?></td>
                                <td><?= $match['ophaal_postcode'] ?></td>
                                <td><?= $match['aflever_postcode'] ?></td>
                                <td><?= explode(' ', $match['date_created'])[0] ?></td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </table>
        </div>
    </div>
</body>

</html>