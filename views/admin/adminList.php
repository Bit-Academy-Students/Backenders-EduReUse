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

// offers & needs
$sql = "SELECT 
    'Offer' AS type,
    offers.id,
    offers.titel,
    product_states.label AS staat,
    offers.hoeveelheid,
    offers.beschrijving,
    offers.postcode,
    NULL AS deadline,
    offers.date_created,
    offers.date_modified,
    types.type AS product_type,
    users.naam AS user_name,
    offers.product_url
FROM offers
INNER JOIN product_states ON offers.staat_id = product_states.id
INNER JOIN types ON offers.type_id = types.id
INNER JOIN users ON offers.user_id = users.id

UNION ALL

SELECT 
    'Need' AS type,
    needs.id AS id,
    needs.titel,
    NULL AS staat,
    needs.hoeveelheid,
    NULL AS beschrijving,
    needs.postcode,
    needs.deadline,
    needs.date_created,
    needs.date_modified,
    types.type AS product_type,
    users.naam AS user_name,
    NULL AS product_url
FROM needs
INNER JOIN types ON needs.type_id = types.id
INNER JOIN users ON needs.user_id = users.id

ORDER BY date_created DESC";

$rows = $conn->query($sql);

$pattern = '/^(?:(?<protocol>[a-z]{2,6})\:\/\/|)?(?<domain>\w.*\.[a-z]{2,})?(?<path>\/(|\w.*))?$/';
$matches = [];

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
                <!-- header -->
                <h1 class="font-bold text-3xl">Alles</h1>

                <div class="flex gap-5 items-baseline">
                    <button type="button">Delete</button>
                    <button type="button">Filters</button>
                    <input id="search"
                        placeholder="Search"
                        type="text"
                        class="bg-slate-100 mt-2 rounded-md shadow-xs rounded-md py-1.5 px-3">
                </div>

                <div></div>
            </div>

            <div id="container">
                <table>
                    <tr>
                        <th>Type</th>
                        <th>ID</th>
                        <th>Titel</th>
                        <th>Product Type</th>
                        <th>Staat</th>
                        <th>Hoeveelheid</th>
                        <th>Beschrijving</th>
                        <th>Postcode</th>
                        <th>Deadline</th>
                        <th>Datum Gecreëerd</th>
                        <th>Datum Gewijzigd</th>
                        <th>Gebruiker</th>
                        <th>URL</th>
                    </tr>

                    <?php if ($rows) { ?>
                        <?php foreach ($rows as $row) { ?>
                            <tr>
                                <td><?= $row['type'] ?></td>
                                <td>
                                    <a href="
                                    <?php if ($row['type'] === 'Need') { ?>
                                        /admin/need/<?= $row['id'] ?>">
                                    <?php } elseif ($row['type'] === 'Offer') { ?>
                                        /admin/offer/<?= $row['id'] ?>">
                                    <?php } ?>
                                    <?= $row['id'] ?? '-' ?>
                                    </a>
                                </td>
                                <td><?= $row['titel'] ?></td>
                                <td><?= $row['product_type'] ?></td>
                                <td><?= $row['staat'] ?? '-' ?></td>
                                <td><?= $row['hoeveelheid'] ?></td>
                                <td><?= $row['beschrijving'] ?? '-' ?></td>
                                <td><?= $row['postcode'] ?></td>
                                <td><?= $row['deadline'] ?? '-' ?></td>
                                <td><?= explode(' ', $row['date_created'])[0] ?></td>
                                <td><?= explode(' ', $row['date_modified'])[0] ?></td>
                                <td><?= $row['user_name'] ?></td>
                                <td>
                                    <?php if (isset($row['product_url'])) :
                                        preg_match($pattern, $row['product_url'], $matches); ?>
                                        <a href="<?= $row['product_url'] ?>"
                                            target="_blank"
                                            class="text-blue-500 hover:underline">
                                            <?= $matches['domain'] . '/...' ?>
                                        </a>
                                    <?php endif ?>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
</body>

</html>