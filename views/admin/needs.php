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

// needs
$sql = "SELECT needs.id, needs.titel, needs.hoeveelheid, needs.postcode, needs.deadline, needs.date_created, needs.date_modified, types.type, users.naam, needs.is_completed
FROM `needs`

INNER JOIN types ON needs.type_id = types.id
INNER JOIN users ON needs.user_id = users.id
ORDER BY needs.id DESC";

$stmt = $conn->prepare($sql);
$stmt->execute();
$needs = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
            <div class="flex items-center justify-around pb-3 mb-5 border-gray-300">
                <h1 class="font-bold text-3xl">Aanvragen</h1>

                <div class="flex gap-5 items-baseline">
                    <button type="button">Delete</button>
                    <button type="button">Filters</button>
                    <input id="search" placeholder="Search" type="text" class="bg-slate-100 mt-2 rounded-md shadow-xs rounded-md py-1.5 px-3">
                </div>
            </div>

            <table>
                <tr class="*:pb-4">
                    <th></th>
                    <th>(school)naam</th>
                    <th>Product type</th>
                    <th>Hoeveelheid</th>
                    <th>Omschrijving</th>
                    <th>Postcode</th>
                    <th>Deadline</th>
                    <td></td>
                </tr>

                <?php if ($needs) { ?>
                    <?php foreach ($needs as $need) { ?>
                        <?php if (!$need['is_completed']) { // only display need if admin hasn't handled the needs
                        ?>
                            <tr class="*:p-4 *:border-t-1 *:border-slate-300 *:text-center">
                                <td>
                                    <a href="/admin/needs/<?= $need['id'] ?>"
                                        class="text-sky-600 font-semibold">
                                        <i class="fa-solid fa-angles-right"></i>
                                    </a>
                                </td>
                                <td class="text-left"><?= $need['naam'] ?></td>
                                <td><?= $need['type'] ?></td>
                                <td><?= $need['hoeveelheid'] ?></td>
                                <td><?= $need['titel'] ?></td>
                                <td><?= $need['postcode'] ?></td>
                                <!-- <td><?= explode(' ', $need['date_created'])[0] ?></td> -->
                                <!-- <td><?= explode(' ', $need['date_modified'])[0] ?></td> -->
                                <td><?= $need['deadline'] ? $need['deadline'] : '-' ?></td>
                                <td>
                                    <a href="/admin/ready-to-match/<?= $need['id'] ?>/<?= lcfirst($need['type']) ?>"
                                        class="text-sky-600 font-semibold cursor-pointer hover:underline">
                                        Match
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
            </table>
        </div>
    </div>
</body>

</html>