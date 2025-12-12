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

// Get offer ID from URL parameter (from router or query string)
// Try multiple methods to get the ID
if (isset($offerId)) {
    // From router variable
} elseif (isset($_GET['id'])) {
    $offerId = $_GET['id'];
} else {
    // Try to extract from URI path
    $uri = $_SERVER['REQUEST_URI'];
    $parts = explode('/', trim($uri, '/'));
    if (count($parts) >= 3 && $parts[0] === 'admin' && $parts[1] === 'offer') {
        $offerId = $parts[2];
    } else {
        $offerId = null;
    }
}

if (!$offerId) {
    header('location: /admin/alles');
    exit();
}

// Get offer details
$sql = "SELECT 
    offers.titel,
    product_states.label AS staat,
    offers.hoeveelheid,
    offers.beschrijving,
    offers.postcode,
    offers.date_created,
    offers.date_modified,
    types.type AS product_type,
    users.naam AS user_name,
    offers.product_url
FROM offers
INNER JOIN product_states ON offers.staat_id = product_states.id
INNER JOIN types ON offers.type_id = types.id
INNER JOIN users ON offers.user_id = users.id
WHERE offers.id = :id";

$stmt = $conn->prepare($sql);
$stmt->execute(['id' => $offerId]);
$offer = $stmt->fetch();

if (!$offer) {
    header('location: /admin/alles');
    exit();
}

$pattern = '/^(?:(?<protocol>[a-z]{2,6})\:\/\/|)?(?<domain>\w.*\.[a-z]{2,})?(?<path>\/(|\w.*))?$/';
$matches = [];

?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offer Details - <?= htmlspecialchars($offer['titel']) ?></title>

    <link rel="stylesheet" href="/src/output.css">
    <?php require_once __DIR__ . '/../components/fontawesome-link.php' ?>
</head>

<body class="bg-gray-100">
    <?php require_once __DIR__ . '/../components/header.php' ?>

    <div class="flex">
        <?php require_once __DIR__ . '/../components/leftSidebar.php' ?>

        <div class="bg-white p-6 rounded-lg m-5 shadow-lg w-full max-w-4xl">
            <!-- terug knop -->
            <div class="flex items-center justify-between pb-4 mb-6 border-b border-gray-300">
                <h1 class="font-bold text-3xl">Offer Details</h1>
                <a href="/admin/alles" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Terug
                </a>
            </div>

            <!-- Offer informatie -->
            <div class="space-y-4">
                <div class="border-b border-gray-200 pb-3">
                    <label class="text-sm font-semibold text-gray-600 uppercase">Gebruiker</label>
                    <p class="text-lg text-gray-800"><?= htmlspecialchars($offer['user_name'] ?? '-') ?></p>
                </div>

                <div class="border-b border-gray-200 pb-3">
                    <label class="text-sm font-semibold text-gray-600 uppercase">Titel</label>
                    <p class="text-lg text-gray-800"><?= htmlspecialchars($offer['titel'] ?? '-') ?></p>
                </div>

                <div class="border-b border-gray-200 pb-3">
                    <label class="text-sm font-semibold text-gray-600 uppercase">Staat</label>
                    <p class="text-lg text-gray-800"><?= htmlspecialchars($offer['staat'] ?? '-') ?></p>
                </div>

                <div class="border-b border-gray-200 pb-3">
                    <label class="text-sm font-semibold text-gray-600 uppercase">Hoeveelheid</label>
                    <p class="text-lg text-gray-800"><?= htmlspecialchars($offer['hoeveelheid']) ?></p>
                </div>

                <div class="border-b border-gray-200 pb-3">
                    <label class="text-sm font-semibold text-gray-600 uppercase">Beschrijving</label>
                    <p class="text-lg text-gray-800"><?= htmlspecialchars($offer['beschrijving'] ?? '-') ?></p>
                </div>

                <div class="border-b border-gray-200 pb-3">
                    <label class="text-sm font-semibold text-gray-600 uppercase">Product Type</label>
                    <p class="text-lg text-gray-800"><?= htmlspecialchars($offer['product_type'] ?? '-') ?></p>
                </div>

                <div class="border-b border-gray-200 pb-3">
                    <label class="text-sm font-semibold text-gray-600 uppercase">Postcode</label>
                    <p class="text-lg text-gray-800"><?= htmlspecialchars($offer['postcode']) ?></p>
                </div>

                <div class="border-b border-gray-200 pb-3">
                    <label class="text-sm font-semibold text-gray-600 uppercase">Datum gecreëerd</label>
                    <p class="text-lg text-gray-800"><?= htmlspecialchars(explode(' ', $offer['date_created'])[0]) ?></p>
                </div>

                <div class="border-b border-gray-200 pb-3">
                    <label class="text-sm font-semibold text-gray-600 uppercase">Laatst aangepast op</label>
                    <p class="text-lg text-gray-800"><?= htmlspecialchars(explode(' ', $offer['date_modified'])[0]) ?></p>
                </div>

                <div class="pb-3">
                    <label class="text-sm font-semibold text-gray-600 uppercase">Product URL</label>

                    <?php if ($offer['product_url']) : ?>
                        <?php preg_match($pattern, $offer['product_url'], $matches); ?>
                        <a href="<?= htmlspecialchars($offer['product_url']) ?>" target="_blank" class="text-lg text-blue-600 hover:text-blue-800 hover:underline break-all">
                            <?= htmlspecialchars($matches['domain']) ?>/
                        </a>
                    <?php else : ?>
                        <p class="text-lg text-gray-800">-</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>