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

// Get need ID from URL parameter 
$needId = $needId ?? null;

if (!$needId) {
    header('location: /admin/alles');
    exit();
}

// Get need details
$sql = "SELECT 
    needs.titel,
    needs.hoeveelheid,
    needs.postcode,
    needs.deadline,
    needs.date_created,
    needs.date_modified,
    types.type AS product_type,
    users.naam AS user_name,
    needs.user_id
FROM needs
INNER JOIN types ON needs.type_id = types.id
INNER JOIN users ON needs.user_id = users.id
WHERE needs.id = :id";

$stmt = $conn->prepare($sql);
$stmt->execute(['id' => $needId]);
$need = $stmt->fetch();

if (!$need) {
    header('location: /admin/alles');
    exit();
}

?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Need Details - <?= htmlspecialchars($need['titel']) ?></title>

    <link rel="stylesheet" href="/../src/output.css">
    <?php require_once __DIR__ . '/../components/fontawesome-link.php' ?>
</head>

<body class="bg-gray-100">
    <?php require_once __DIR__ . '/../components/header.php' ?>

    <div class="flex">
        <?php require_once __DIR__ . '/../components/leftSidebar.php' ?>

        <div class="bg-white p-6 rounded-lg m-5 shadow-lg w-full max-w-4xl">
            <!-- terug knop -->
            <div class="flex items-center justify-between pb-4 mb-6 border-b border-gray-300">
                <h1 class="font-bold text-3xl">Aanvraag Details</h1>
                <a href="/admin/alles" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Terug
                </a>
            </div>

            <!-- Need informatie -->
            <div class="space-y-4">

                <div class="border-b border-gray-200 pb-3">
                    <label class="text-sm font-semibold text-gray-600 uppercase">Gebruiker</label>
                    <p class="text-lg text-gray-800"><?= htmlspecialchars($need['user_name'] ?? '-') ?></p>
                </div>

                <div class="border-b border-gray-200 pb-3">
                    <label class="text-sm font-semibold text-gray-600 uppercase">Titel</label>
                    <p class="text-lg text-gray-800"><?= htmlspecialchars($need['titel'] ?? '-') ?></p>
                </div>

                <div class="border-b border-gray-200 pb-3">
                    <label class="text-sm font-semibold text-gray-600 uppercase">Hoeveelheid</label>
                    <p class="text-lg text-gray-800"><?= htmlspecialchars($need['hoeveelheid']) ?></p>
                </div>

                <div class="border-b border-gray-200 pb-3">
                    <label class="text-sm font-semibold text-gray-600 uppercase">Product Type</label>
                    <p class="text-lg text-gray-800"><?= htmlspecialchars($need['product_type'] ?? '-') ?></p>
                </div>

                <div class="border-b border-gray-200 pb-3">
                    <label class="text-sm font-semibold text-gray-600 uppercase">Postcode</label>
                    <p class="text-lg text-gray-800"><?= htmlspecialchars($need['postcode']) ?></p>
                </div>

                <div class="border-b border-gray-200 pb-3">
                    <label class="text-sm font-semibold text-gray-600 uppercase">Deadline</label>
                    <p class="text-lg text-gray-800"><?= htmlspecialchars($need['deadline'] ?? '-') ?></p>
                </div>

                <div class="border-b border-gray-200 pb-3">
                    <label class="text-sm font-semibold text-gray-600 uppercase">Datum gecreëerd</label>
                    <p class="text-lg text-gray-800"><?= htmlspecialchars(explode(' ', $need['date_created'])[0]) ?></p>
                </div>

                <?php if (explode(' ', $need['date_created'])[0] !== explode(' ', $need['date_modified'])[0]) : ?>
                    <div class="border-b border-gray-200 pb-3">
                        <label class="text-sm font-semibold text-gray-600 uppercase">Laatst aangepast op</label>
                        <p class="text-lg text-gray-800"><?= htmlspecialchars(explode(' ', $need['date_modified'])[0]) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>