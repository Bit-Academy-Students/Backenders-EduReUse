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

$needs = [];
$offers = [];
if ($rows) {
    foreach ($rows as $row) {
        if (($row['type'] ?? '') === 'Need') {
            $needs[] = $row;
        } elseif (($row['type'] ?? '') === 'Offer') {
            $offers[] = $row;
        }
    }
}

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
            <div class="flex items-center justify-between pb-3 mb-5 border-b border-gray-300">
                <!-- header -->
                <h1 class="font-bold text-3xl">Alles</h1>

                <div class="flex gap-4 items-center">
                    <button type="button" class="px-3 py-1 bg-red-100 rounded hover:bg-red-200">Delete</button>
                    <button type="button" class="px-3 py-1 bg-gray-100 rounded hover:bg-gray-200">Filters</button>
                    <input id="search" placeholder="Search" type="text" class="bg-slate-100 rounded-md shadow-xs py-1.5 px-3">
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Needs column -->
                <div class="w-full lg:w-1/2">
                    <h2 class="font-semibold text-xl mb-3 text-black-600">Aanvragen</h2>
                    <div class="space-y-4">
                        <?php if (!empty($needs)) : ?>
                            <?php foreach ($needs as $need) : ?>
                                <a href="/admin/need/<?= htmlspecialchars($need['id']) ?>" class="block">
                                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md hover:border-black-400 cursor-pointer transition-all">
                                        <h3 class="text-lg font-medium text-gray-800 mb-2">
                                            <?= htmlspecialchars($need['titel'] ?? '-') ?>
                                        </h3>

                                        <div class="space-y-1 text-sm text-gray-600">
                                            <div><strong>Gebruiker:</strong> <?= htmlspecialchars($need['user_name'] ?? '-') ?></div>
                                            <div><strong>Postcode:</strong> <?= htmlspecialchars($need['postcode'] ?? '-') ?></div>
                                            <div><strong>Type:</strong> <?= htmlspecialchars($need['product_type'] ?? '-') ?></div>
                                            <div><strong>Datum gecreëerd:</strong> <?= htmlspecialchars(explode(' ', $need['date_created'])[0] ?? '-') ?></div>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <p class="text-gray-600">Geen aanvragen gevonden.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Offers column -->
                <div class="w-full lg:w-1/2">
                    <h2 class="font-semibold text-xl mb-3 text-black-600">Offers</h2>
                    <div class="space-y-4">
                        <?php if (!empty($offers)) : ?>
                            <?php foreach ($offers as $offer) : ?>
                                <a href="/admin/offer/<?= htmlspecialchars($offer['id']) ?>" class="block">
                                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md hover:border-black-400 cursor-pointer transition-all">
                                        <h3 class="text-lg font-medium text-gray-800 mb-2">
                                            <?= htmlspecialchars($offer['titel'] ?? '-') ?>
                                        </h3>

                                        <div class="space-y-1 text-sm text-gray-600">
                                            <div><strong>Gebruiker:</strong> <?= htmlspecialchars($offer['user_name'] ?? '-') ?></div>
                                            <div><strong>Postcode:</strong> <?= htmlspecialchars($offer['postcode'] ?? '-') ?></div>
                                            <div><strong>Type:</strong> <?= htmlspecialchars($offer['product_type'] ?? '-') ?></div>
                                            <div><strong>Datum gecreëerd:</strong> <?= htmlspecialchars(explode(' ', $offer['date_created'])[0] ?? '-') ?></div>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <p class="text-gray-600">Geen offers gevonden.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>

</html>